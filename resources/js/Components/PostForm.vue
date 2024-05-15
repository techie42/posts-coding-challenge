<script setup>
    import {
        reactive,
        ref,
    } from 'vue';
    import {
        router,
        useForm,
    } from '@inertiajs/vue3';
    import InputLabel from "@/Components/InputLabel.vue";
    import TextInput from "@/Components/TextInput.vue";
    import InputError from "@/Components/InputError.vue";
    import PrimaryButton from "@/Components/PrimaryButton.vue";

    defineProps({
        errors: Object,
    });

    const titleInput = ref(null);
    const slugInput = ref(null);

    const form = useForm({
        title: '',
        slug: '',
    });

    /**
     * https://gist.github.com/joseluisq/819d694db6fd675deae7270b4e55b3ac
     */
    const slugify = (text, ampersand = 'and') => {
        const a = 'àáäâèéëêìíïîòóöôùúüûñçßÿỳýœæŕśńṕẃǵǹḿǘẍźḧ'
        const b = 'aaaaeeeeiiiioooouuuuncsyyyoarsnpwgnmuxzh'
        const p = new RegExp(a.split('').join('|'), 'g')

        return text.toString().toLowerCase()
            .replace(/[\s_]+/g, '-')
            .replace(p, c =>
                b.charAt(a.indexOf(c)))
            .replace(/&/g, `-${ampersand}-`)
            .replace(/[^\w-]+/g, '')
            .replace(/--+/g, '-')
    };

    const filterInputForSlug = (e) => {
        let t = e.target;
        t.value = slugify(t.value);
    };

    function submit() {
        router.post('/', form, {
            preserveScroll: true,
            onSuccess: (page) => form.reset(),
            onError: (errors) => {
                console.log(errors);
            },
        });
    }
    // };
</script>

<template>
    <form @submit.prevent="submit" class="mt-6 space-y-6">
        <fieldset>
            <legend class="py-4">Create a new post:</legend>

            <InputLabel
                for="title"
                value="Title"
            />

            <TextInput
                id="title"
                ref="titleInput"
                v-model="form.title"
                type="text"
                class="mt-1 block w-full"
                placeholder="max 255 chars"
            />

            <InputError
                :message="$page.props.errors.title"
                class="mt-2"
            />


            <InputLabel
                for="slug"
                value="Slug"
            />

            <TextInput
                id="slug"
                ref="slugInput"
                v-model="form.slug"
                type="text"
                class="mt-1 block w-full"
                @input="filterInputForSlug"
                placeholder="max 255 chars; only certain characters will be allowed to comply with slug format"
            />

            <InputError
                :message="$page.props.errors.slug"
                class="mt-2"
            />
        </fieldset>

        <div class="flex items-center gap-4">
            <PrimaryButton
                :disabled="form.processing"
            >
                Save post
            </PrimaryButton>

            <Transition
                enter-active-class="transition ease-in-out"
                enter-from-class="opacity-0"
                leave-active-class="transition ease-in-out"
                leave-to-class="opacity-0"
            >
                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Post saved</p>
            </Transition>
        </div>
    </form>
</template>

<style scoped>
    ::placeholder {
        @apply text-gray-300 italic text-sm;
    }
</style>
