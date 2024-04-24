<script setup>
import { ref, onMounted, reactive, watch, computed } from "vue";
import { useToastr } from "@/toastr";
import ContentLoader from "@/components/ContentLoader.vue";
import axios from "axios";
import { Form, Field, useResetForm } from "vee-validate";
import * as yup from "yup";
import DataTablesCore from "datatables.net";
import DataTable from "datatables.net-vue3";
import Buttons from "datatables.net-buttons-bs4";
import ButtonsHtml5 from "datatables.net-buttons/js/buttons.html5";
import print from "datatables.net-buttons/js/buttons.print";
import pdfmake from "pdfmake";
import pdfFonts from "pdfmake/build/vfs_fonts";
import { useAuthUserStore } from "../../../stores/AuthUserStore";
pdfmake.vfs = pdfFonts.pdfMake.vfs;
import "datatables.net-responsive-bs4";
import JsZip from "jszip";
import FormCheckRadioGroup from "@/Components/FormCheckRadioGroup.vue";
import moment from "moment";
import { useRoute } from "vue-router";

DataTable.use(pdfmake);
DataTable.use(ButtonsHtml5);
DataTable.use(DataTablesCore);

const toastr = useToastr();
const pageTitle = `${useRoute().name}`;
const checked = ref(true);
const isLoadingSite = ref(false);
const listItem = ref([]);
const columns = [
    { data: "id" },
    { data: "loccode" },
    { data: "locationgroup" },
    { data: "abccode" },
    { data: "description" },
    { data: "is_active" },
    { data: "created_by", sortable: false },
    { data: "created_at" },
    {
        data: "id",
        render: "#action",
        title: "Action",
        sortable: false,
    },
];

const editing = ref(false);
const form = reactive({
    id: "",
    code: "",
    description: "",
    is_active: true,
});
const createDataSchema = yup.object({
    code: yup.string().required(),
    description: yup.string().required(),
});

const editDataSchema = yup.object({
    code: yup.string().required(),
    description: yup.string().required(),
});

const getData = () => {
    axios.get(`/web/storage-location`).then((response) => {
        listItem.value = response.data;
    });
};
const addItem = () => {
    editing.value = false;
    $("#FormModal").modal("show");
};
const handleSubmit = (values, actions) => {
    if (editing.value) {
        updateRecord(values, actions);
    } else {
        createRecord(values, actions);
    }
};
const createRecord = (values, { resetForm, setErrors }) => {
    axios
        .post("/web/storage-location", values)
        .then((response) => {
            getData();
            $("#FormModal").modal("hide");
            resetForm();
            toastr.success(response.data.message);
        })
        .catch((error) => {
            if (error.response.data.errors) {
                setErrors(error.response.data.errors);
                toastr.warning(error.response.data.errors.name);
            }
        });
};

const updateRecord = ({ setErrors }) => {
    axios
        .post("/web/storage-location", form)
        .then((response) => {
            getData();
            $("#FormModal").modal("hide");
            toastr.success(response.data.message);
        })
        .catch((error) => {
            setErrors(error.response.data.errors);
            console.log(error);
        });
};

const editItem = (item) => {
    editing.value = true;
    form.id = item.id;
    form.code = item.code;
    form.description = item.description;

    if (form.is_active === 1) {
        checked.value = true;
    } else {
        checked.value = false;
    }
    form.is_active = item.is_active;
    $("#FormModal").modal("show");
};

const deleteItem = (id) => {
    isLoadingSite.value = true;
    axios
        .delete(`/web/storage-location/${id}`)
        .then((response) => {
            isLoadingSite.value = false;
            getData();
        })
        .catch((error) => {
            console.log(error);
        });
};

const getkey = () =>{
    axios
        .delete(`/web/getkey/MOVEMENT`)
        .then((response) => {
            isLoadingSite.value = false;
            getData();
        })
        .catch((error) => {
            console.log(error);
        });
}
onMounted(() => {
    getData();
    document.title = pageTitle;
});
</script>
<template>
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <DataTable
                            class="mt-2 table table-sm table-hover table-striped display"
                            :columns="columns"
                            :data="listItem"
                            :options="{
                                responsive: true,
                                autoWidth: false,
                                lengthMenu: [
                                    [10, 25, 50, -1],
                                    [10, 25, 50, 'All'],
                                ],
                                language: {
                                    entries: {
                                        _: `${pageTitle}`,
                                        1: `${pageTitle}`,
                                    },
                                },
                            }"
                        >
                            <template #action="props">
                                <div>
                                    <Button
                                        class="btn btn-sm btn-primary"
                                        @click="editItem(props.rowData)"
                                        >Edit</Button
                                    >&nbsp;
                                    <Button
                                        class="btn btn-sm btn-danger"
                                        @click="deleteItem(props.rowData.id)"
                                        >Delete</Button
                                    >
                                </div>
                            </template>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Location Code</th>
                                    <th>Location group</th>
                                    <th>abccode</th>
                                    <th>Description</th>
                                    <th>Active</th>
                                    <th>Created By</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div
        class="modal fade"
        id="FormModal"
        data-backdrop="static"
        tabindex="-1"
        role="dialog"
        aria-labelledby="staticBackdropLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <span v-if="editing">Edit {{ pageTitle }}</span>
                        <span v-else>Add {{ pageTitle }}</span>
                    </h5>
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <Form
                    @submit="handleSubmit"
                    :validation-schema="
                        editing ? editDataSchema : createDataSchema
                    "
                    v-slot="{ errors }"
                    :initial-values="form"
                >
                    <div class="modal-body">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-6">
                                    
                               
                            <Field
                                type="hidden"
                                name="created_by"
                                id="created_by"
                                v-model="form.created_by"
                            />

                            <div class="form-group">
                                <label for="loccode">Location Code</label>
                                <Field
                                    name="loccode"
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': errors.loccode,
                                    }"
                                    id="loccode"
                                    aria-describedby="nameHelp"
                                    placeholder="Enter Location Code"
                                    v-model="form.loccode"
                                />
                                <span class="invalid-feedback">{{
                                    errors.loccode
                                }}</span>
                            </div>

                            <div class="form-group">
                                        <label for="branch">Location Group</label>
                                        <Field
                                            as="select"
                                            name="locationgroup"
                                            class="form-control"
                                            :class="{
                                                'is-invalid': errors.locationgroup,
                                            }"
                                            id="locationgroup"
                                            aria-describedby="branchHelp"
                                            v-model="form.locationgroup"
                                        >
                                            <option value="" disabled>
                                                Select Location Group
                                            </option>

                                            <option
                                                v-for="item in listlocgroups"
                                                :key="item.site_name"
                                                :value="item.site_name"
                                            >
                                                {{ site.locationgroup }}
                                            </option>
                                        </Field>
                                        <span class="invalid-feedback">{{
                                            errors.branch
                                        }}</span>
                                    </div>
                                    <div class="form-group">
                                <label for="locationtype">Location type</label>
                                <Field
                                    name="locationtype"
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': errors.locationtype,
                                    }"
                                    id="locationtype"
                                    aria-describedby="nameHelp"
                                    placeholder="Enter Location Type"
                                    v-model="form.locationtype"
                                />
                                <span class="invalid-feedback">{{
                                    errors.locationtype
                                }}</span>
                            </div>
                        </div>
                            <div class="col-6">
                                <div class="form-group">
                                <label for="loccode">ABC Code</label>
                                <Field
                                    name="loccode"
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': errors.loccode,
                                    }"
                                    id="loccode"
                                    aria-describedby="nameHelp"
                                    placeholder="Enter Location Code"
                                    v-model="form.loccode"
                                />
                                <span class="invalid-feedback">{{
                                    errors.loccode
                                }}</span>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <Field
                                    name="description"
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': errors.description,
                                    }"
                                    id="description"
                                    aria-describedby="nameHelp"
                                    placeholder="Enter description"
                                    v-model="form.description"
                                />
                                <span class="invalid-feedback">{{
                                    errors.description
                                }}</span>
                            </div>
                            <div class="form-group">
                                <label for="batchcapacity">Batch Capacity</label>
                                <Field
                                    name="batchcapacity"
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': errors.batchcapacity,
                                    }"
                                    id="batchcapacity"
                                    aria-describedby="nameHelp"
                                    placeholder="Enter Batch Capacity"
                                    v-model="form.batchcapacity"
                                />
                                <span class="invalid-feedback">{{
                                    errors.batchcapacity
                                }}</span>
                            </div>
                        </div>
                    </div>
                            <div class="form-group">
                                <FormCheckRadioGroup
                                    v-model="form.is_active"
                                    name="is_active"
                                    :options="{ is_active: 'Active' }"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal"
                        >
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>
                    </div>
                </Form>
            </div>
        </div>
    </div>

    <div class="fab-container">
        <div class="iconbutton">
            <i class="fas fa-plus" @click="addItem()"></i>
        </div>
    </div>
</template>
