<template>
    <h1 class="text-3xl mb-4">Your Listings</h1>
    <section class="mb-8"><RealtorFilters :filters="filters" /></section>
    <section
        v-if="listings.data.length"
        class="grid grid-cols-1 lg:grid-cols-2 gap-2"
    >
        <Box
            v-for="listing in listings.data"
            :key="listing.id"
            :class="{ 'border-dashed': listing.deleted_at }"
        >
            <div
                class="flex flex-col md:flex-row gap-2 md:items-center justify-between"
            >
                <div :class="{ 'opacity-25': listing.deleted_at }">
                    <div
                        v-if="listing.sold_at !== null"
                        class="text-xs font-bold uppercase border border-dashed p-1 border-green-300 text-green-500 dark:border-green-600 dark:text-green-600 inline-block rounded-md mb-2"
                    >
                        sold
                    </div>
                    <div class="xl:flex items-center gap-2">
                        <Price
                            :price="listing.price"
                            class="text-2xl font-medium"
                        ></Price>
                        <ListingSpace :listing="listing" />
                    </div>
                    <ListingAddress :listing="listing" class="text-gray-500" />
                </div>
                <section>
                    <div
                        class="flex items-center gap-1 text-gray-600 dark:text-gray-300"
                    >
                        <a
                            :href="
                                route('listing.show', { listing: listing.id })
                            "
                            target="_blank"
                            class="btn-outline text-xs font-medium"
                            >Preview</a
                        >
                        <Link
                            :href="
                                route('realtor.listing.edit', {
                                    listing: listing.id,
                                })
                            "
                            class="btn-outline text-xs font-medium"
                            >Edit</Link
                        >
                        <Link
                            v-if="!listing.deleted_at"
                            class="btn-outline text-xs font-medium"
                            :href="
                                route('realtor.listing.destroy', {
                                    listing: listing.id,
                                })
                            "
                            as="button"
                            method="delete"
                            >Delete</Link
                        >
                        <Link
                            v-else
                            class="btn-outline text-xs font-medium"
                            :href="
                                route('realtor.listing.restore', {
                                    listing: listing.id,
                                })
                            "
                            as="button"
                            method="put"
                            >Restore</Link
                        >
                    </div>
                    <div class="mt-2">
                        <Link
                            class="block w-full btn-outline text-xs font-medium text-center"
                            :href="
                                route('realtor.listing.image.create', {
                                    listing: listing.id,
                                })
                            "
                            >Images ({{ listing.images_count }})</Link
                        >
                    </div>
                    <div class="mt-2">
                        <Link
                            class="block w-full btn-outline text-xs font-medium text-center"
                            :href="
                                route('realtor.listing.show', {
                                    listing: listing.id,
                                })
                            "
                            >Offers ({{ listing.offers_count }})</Link
                        >
                    </div>
                </section>
            </div>
        </Box>
    </section>

    <EmptyState v-else>No listings yet</EmptyState>

    <section
        v-if="listings.data.length"
        class="w-full flex justify-center mt-4 mb-4"
    >
        <Pagination :links="listings.links" />
    </section>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import Box from "@/Components/UI/Box.vue";
import Pagination from "@/Components/UI/Pagination.vue";
import EmptyState from "@/Components/UI/EmptyState.vue";
import Price from "@/Components/Price.vue";
import ListingSpace from "@/Components/ListingSpace.vue";
import ListingAddress from "@/Components/ListingAddress.vue";
import RealtorFilters from "@/Pages/Realtor/Index/Components/RealtorFilters.vue";
defineProps({
    listings: Object,
    filters: Object,
});
</script>

<script>
import MainLayout from "@/Layouts/MainLayout.vue";

export default {
    layout: MainLayout,
};
</script>
