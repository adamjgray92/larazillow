<template>
    <Box>
        <template #header>Upload New Images</template>
        <form @submit.prevent="upload">
            <section class="flex items-center gap-2 my-4">
                <input
                    ref="fileInput"
                    class="border rounded-md file:px-4 file:py-2 border-gray-200 dark:border-gray-700 file:text-gray-700 file:dark:text-gray-400 file:border-0 file:bg-gray-100 file:dark:bg-gray-800 file:font-medium file:bg-gray-200 file:dark:hover:bg-gray-700 file:hover:cursor-pointer file:mr-4"
                    type="file"
                    multiple
                    @input="addFiles"
                />
                <button
                    type="submit"
                    class="btn-outline disabled:opacity-25 disabled:cursor-not-allowed"
                    :disabled="!canUpload"
                >
                    Upload
                </button>
                <button type="reset" @click="reset" class="btn-outline">
                    Reset
                </button>
            </section>
            <div v-if="imageErrors.length" class="input-error">
                <div v-for="(error, index) in imageErrors" :key="index">
                    {{ error }}
                </div>
            </div>
        </form>
    </Box>

    <Box v-if="listing.images.length > 0" class="mt-4">
        <template #header>Current Listing Images</template>
        <section class="mt-4 grid grid-cols-3 gap-4">
            <div
                v-for="image in listing.images"
                :key="image.id"
                class="flex flex-col justify-between"
            >
                <img :src="image.src" class="rounded-md" />
                <Link
                    method="delete"
                    :href="
                        route('realtor.listing.image.destroy', {
                            listing: listing.id,
                            image: image.id,
                        })
                    "
                    class="btn-outline text-sm mt-2"
                    >Delete</Link
                >
            </div>
        </section>
    </Box>
</template>

<script setup>
import { computed, ref } from "vue";
import { useForm, router, Link } from "@inertiajs/vue3";
import NProgress from "nprogress";
import Box from "@/Components/UI/Box.vue";

const props = defineProps({ listing: Object });

router.on("progress", (event) => {
    if (event.detail.progress.percentage) {
        NProgress.set((event.detail.progress.percentage / 100) * 0.9);
    }
});

const fileInput = ref(null);

const form = useForm({
    images: [],
});

const imageErrors = computed(() => Object.values(form.errors));
const canUpload = computed(() => form.images.length);

const upload = () =>
    form.post(
        route("realtor.listing.image.store", { listing: props.listing.id }),
        {
            onSuccess: () => {
                fileInput.value.value = null;
                form.reset("images");
            },
        }
    );

const addFiles = (event) => {
    for (const image of event.target.files) {
        form.images.push(image);
    }
};

const reset = () => form.reset("images");
</script>

<script>
import MainLayout from "@/Layouts/MainLayout.vue";

export default {
    layout: MainLayout,
    components: { Box },
};
</script>
