<script setup>
import { ref, onMounted, reactive, watch, computed } from "vue";
import { useToastr } from "@/toastr";
import ContentLoader from "../../components/ContentLoader.vue";
import { Form, Field, useResetForm } from "vee-validate";
import { useRoute } from "vue-router";
import WarehouseSetting from './WarehouseSetting.vue';
const pageTitle = `${useRoute().name}`;
const settings = ref([]);
const toastr = useToastr();
const showList = ref(true);
const listItem = ref([]);

const formValues = ref();
//site
const formSite = ref({
    id: "",
    site_name: "",
    is_active: "",
});

const getItems = () => {
    isLoadingSite.value = true;
    axios
        .get(`/api/site`)
        .then((response) => {
            isLoadingSite.value = false;
            listItem.value = response.data;
        })
        .catch((error) => {
            console.log(error);
        });
};

const removeSite = () => {
    isLoadingSite.value = true;
    axios
        .delete(`/api/site/`  )
        .then((response) => {
            isLoadingSite.value = false;
            listItem.value = response.data;
        })
        .catch((error) => {
            console.log(error);
        });
};

const deActivatedTask = computed(() => {
    if (!Array.isArray(listItem.value)) {
        return [];
    }

    return listItem.value.filter((item) => item.is_active === 0);
});

const deactivelist = computed(() => deActivatedTask.value.length);
const toggleList = () => {
    showList.value = !showList.value;
};


// const createData = (values, { resetForm, setErrors }) => {
//    axios
//         .post('/api/site', formSite)
//         .then((response) => {
//           // Assuming getItems is a function to fetch data
//           getItems();
//           toastr.success(response.data.message);
//           isLoadingSite.value = false;
//         })
//         .catch((error) => {
//           if (error.response && error.response.status === 422) {
//             errors.value = error.response.data.errors;
//           }
//         });
// };

// click task item to complete or cancel
const handleIsActiveSite = (item) => {

    formSite.value.is_active = item.is_active === 1 ? 0 : 1;

    axios
        .post("/api/site", {
            id: item.id,
            site_name: item.site_name,
            is_active: formSite.value.is_active,
        })
        .then((response) => {
            // Fetch tasks using GET request after the POST request is successful
            axios
                .get(`/api/site`)
                .then((response) => {
                    listItem.value = response.data;
                    //toastr.success("Data created successfully!");
                })
                .catch((error) => {
                    console.error("Error fetching tasks:", error);
                    toastr.error("Error fetching tasks. Please try again.");
                });
        })
        .catch((error) => {
            console.error("Error adding new task:", error);
            toastr.error("Error adding new task. Please try again.");
        });
};

const editData = (item) => {

    formSite.value.id = item.id;
    formSite.value.site_name = item.site_name;
}
const getSettings = () => {
    axios.get("/api/settings").then((response) => {
        settings.value = response.data;
    });
};

const errors = ref();
const updateSettings = () => {
    errors.value = "";
    axios
        .post("/api/settings", settings.value)
        .then((response) => {
            toastr.success("Settings updated successfully!");
        })
        .catch((error) => {
            if (error.response && error.response.status === 422) {
                errors.value = error.response.data.errors;
            }
        });
};

onMounted(() => {
    getSettings();
    document.title = pageTitle;
});
</script>

<template>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills" >
                                <li class="nav-item"><a class="nav-link active" href="#setting" data-toggle="tab"><i
                                            class="fas fa-cog mr-1"></i>Setting</a></li>
                                <li class="nav-item"><a class="nav-link" href="#site" data-toggle="tab"><i
                                            class="fas fa-warehouse mr-1"></i> Warehouse</a></li>
                            </ul>
                        </div>

                        <div class="tab-content">
                            <!-- General Settings Tab -->
                            <div class="tab-pane fade show active" id="setting">
                                <form @submit.prevent="updateSettings()">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="appName"
                                                >App Display Name</label
                                            >
                                            <input
                                                v-model="settings.app_name"
                                                type="text"
                                                class="form-control"
                                                id="appName"
                                                placeholder="Enter app display name"
                                            />
                                            <span
                                                class="text-danger text-sm"
                                                v-if="errors && errors.app_name"
                                                >{{ errors.app_name[0] }}</span
                                            >
                                        </div>
                                        <div class="form-group">
                                            <label for="dateFormat"
                                                >Date Format</label
                                            >
                                            <select
                                                v-model="settings.date_format"
                                                class="form-control"
                                            >
                                                <option value="m/d/Y">
                                                    MM/DD/YYYY
                                                </option>
                                                <option value="d/m/Y">
                                                    DD/MM/YYYY
                                                </option>
                                                <option value="Y-m-d">
                                                    YYYY-MM-DD
                                                </option>
                                                <option value="F j, Y">
                                                    Month DD, YYYY
                                                </option>
                                                <option value="j F Y">
                                                    DD Month YYYY
                                                </option>
                                            </select>
                                            <span
                                                class="text-danger text-sm"
                                                v-if="
                                                    errors && errors.date_format
                                                "
                                                >{{
                                                    errors.date_format[0]
                                                }}</span
                                            >
                                        </div>
                                        <div class="form-group">
                                            <label for="paginationLimit"
                                                >Pagination Limit</label
                                            >
                                            <input
                                                v-model="
                                                    settings.pagination_limit
                                                "
                                                type="text"
                                                class="form-control"
                                                id="paginationLimit"
                                                placeholder="Enter pagination limit"
                                            />
                                            <span
                                                class="text-danger text-sm"
                                                v-if="
                                                    errors &&
                                                    errors.pagination_limit
                                                "
                                                >{{
                                                    errors.pagination_limit[0]
                                                }}</span
                                            >
                                        </div>
                                    </div>

                                    <div class="card-footer">
                                        <button
                                            type="submit"
                                            class="btn btn-primary"
                                        >
                                            <i class="fa fa-save mr-1"></i>Save
                                            Changes
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Site Settings Tab -->
                            <div class="tab-pane fade p-2" id="site">

                                <WarehouseSetting/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
a {
    color: #2b2b2b;
    text-decoration: none;
}
.nav-tabs {
    border-bottom: 2px solid #2196f3;
}

.nav-tabs .nav-item {
    margin-bottom: -1px;
}

.nav-tabs .nav-link {
    border: 1px solid transparent;
    border-radius: 0;
    color: #2196f3;
    transition: background-color 0.3s;
}

.nav-tabs .nav-link.active {
    background-color: #2196f3;
    color: #fff;
    border-color: #2196f3;
}

.nav-tabs .nav-link:hover {
    background-color: #0069d9;
    border-color: #0069d9;
}
</style>
