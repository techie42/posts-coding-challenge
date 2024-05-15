<script setup>
    import ThumbUpIconHollow from "@/Components/ThumbUpIconHollow.vue";
    import ThumbUpIconSolid from "@/Components/ThumbUpIconSolid.vue";
    import {router} from "@inertiajs/vue3";

    defineProps({
        like: {
            type: Boolean,
            default: false,
        },
        ident: {
            type: String,
            default: null,
        },
    });

    function toggleLike(ident) {
        router.post('/like', {
            ident: ident,
        }, {
            preserveScroll: true,
        });
    }
</script>

<template>
    <button
        @click="toggleLike(ident)"
    >
        <ThumbUpIconSolid v-if="like" />
        <ThumbUpIconHollow v-else />
    </button>
</template>

