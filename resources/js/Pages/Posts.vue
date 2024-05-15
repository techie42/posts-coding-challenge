<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';
import PostForm from "@/Components/PostForm.vue";
import PostList from "@/Components/PostList.vue";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
    posts: {
        type: Array,
        default: () => [],
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}

</script>

<template>
    <Head title="Posts - Coding Challenge" />
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <div
            class="relative flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white"
        >
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                    <div class="flex lg:justify-center lg:col-start-2">
                        <p>Posts - Coding Challenge</p>
                    </div>
                    <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-end">
                        <Link v-if="$page.props.auth.user"
                            :href="route('logout')"
                            method="post"
                            as="button"
                        >
                            Log Out
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Log in
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Register
                            </Link>
                        </template>
                    </nav>
                </header>

                <main class="mt-6">
                    <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
                        <p class="font-bold">Before you begin</p>
                        <p>- Anyone can create a post, but you can only "like" posts if you're logged-in</p>
                        <p>- The slug must conform to specific standards, so we'll help you by only permitting certain characters</p>
                        <p>- There is no pagination for the list of posts - if demand for this site grows, we'll implement this in version 2</p>
                        <p>- The post list is sorted so the most recent one shows at the top</p>
                        <p>- Feel free to register so you can login</p>
                        <p>- Have fun!</p>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-1 lg:gap-8">
                        <PostForm />
                    </div>

                    <div class="grid gap-6 lg:grid-cols-1 lg:gap-8 mt-8">
                        <PostList :posts="posts" />
                    </div>
                </main>
            </div>
        </div>
    </div>
</template>
