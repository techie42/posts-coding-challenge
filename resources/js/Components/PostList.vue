<script setup>
    import {
        ref,
        watch,
    } from "vue";
    import {router} from "@inertiajs/vue3";
    import LikeButton from "@/Components/LikeButton.vue";

    defineProps({
        posts: {
            type: Array,
            default: () => [],
        },
    });

    const debounce = ((fn, wait) => {
        let timer;

        return function(...args) {
            if (timer) {
                clearTimeout(timer);
            }

            const context = this;
            timer = setTimeout(() => {
                fn.apply(context, args);
            }, wait);
        }
    });

    const tableHeaders = [
        "Slug",
        "Title",
        "Date Published",
    ];

    const searchField = ref(null);
    const url = route('post.list');

    watch(searchField, debounce(() => {
        router.get(url, { searchTerm: searchField.value }, { preserveState: true, preserveScroll: true, only: [ 'posts' ]})
    }, 300));
</script>

<template>
    <input id="search" type="search" v-model="searchField" class="mt-1 block w-full" placeholder="Filter the results list">

    <table class="shadow-lg">
        <thead>
            <tr>
                <th colspan="5" class="title">Published Posts</th>
            </tr>
            <tr>
                <th v-for="header in tableHeaders" :key="header">
                    {{ header }}
                </th>

                <th class="tally">
                    Total Likes
                </th>

                <th v-if="$page.props.auth.user" class="tally">
                    Like
                </th>

                <th v-else class="tally">

                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="post in posts" :key="post.id">
                <td class="border px-4 py-2">{{ post.slug }}</td>
                <td class="border px-4 py-2">{{ post.title }}</td>
                <td class="border px-4 py-2">{{ post.formatted_published_at }}</td>

                <td v-if="post.users_count > 0" class="border px-4 py-2 tally">{{ post.users_count }}</td>
                <td v-else class="border px-4 py-2 tally text-xs italic">None yet</td>

                <td v-if="$page.props.auth.user" class="tally">
                    <LikeButton :like="post.is_my_like" :ident="post.id" />
                </td>
                <td v-else></td>
            </tr>
            <tr v-if="posts.length === 0">
                <td colspan="3" class="border px-4 py-2 text-center bg-gray-100 text-gray-800">There are no matches based on your search term</td>
            </tr>
        </tbody>
    </table>
</template>

<style scoped>
    th {
        @apply bg-green-100 border text-left px-4 py-4;
    }

    th.title {
        @apply bg-blue-100 text-center px-4 py-4;
    }

    th.tally {
        @apply text-center;
    }

    td.tally {
        @apply text-center;
    }

    ::placeholder {
        @apply text-gray-300 italic text-sm;
    }
</style>
