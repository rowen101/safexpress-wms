<!-- App.vue -->

<template>
    <div>
        <MainLayout v-if="!is404 && !isTechRecomApprovePage" />
        <ErrorLayout v-else />
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useAuthUserStore } from "./stores/AuthUserStore";
import { useRoute } from "vue-router"; // Import useRoute function
const authUserStore = useAuthUserStore();
const route = useRoute(); // Access the current route
import MainLayout from "./CustomMainLayout.vue"; // Import the CustomMainLayout component
import ErrorLayout from "./404.vue"; // Import the ErrorLayout component
import TechRecomApprove from "./pages/techrecomm/TechRecommApprove.vue";

// Check if the current route is the TechRecomApprove page
const isTechRecomApprovePage = computed(() => {
    return route.name === "Tech-Approved"; // Adjust the route name as per your route configuration
});

const is404 = computed(() => {
    return route.name === "404 Error Page";
});

const deleteItem = async (id) => {
    const result = await swal({
        title: "Are you sure?",
        text: "You wanna Delete this Record?",
        icon: "warning",
        showCancelButton: true,
    });

    // Check if the user confirmed
    if (result.isConfirmed) {
        isloading.value = true;
        axios
            .delete(`/web/warehouse/${id}`)
            .then((response) => {
                isLoadingSite.value = false;
                toastr.success("Record Delete successfully!");
                getData();
            })
            .catch((error) => {
                console.log(error);
            });
    }
};
</script>




<style>
@import "datatables.net-bs4";
</style>
