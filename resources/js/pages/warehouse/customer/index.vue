<script setup>
import axios from "axios";
import { ref, onMounted, reactive, watch } from "vue";
import { Form, Field, useResetForm } from "vee-validate";
import * as yup from "yup";
import { useToastr } from "../../../toastr.js";
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
import FormCheckRadioGroup from '@/Components/FormCheckRadioGroup.vue'
import { useRoute } from "vue-router";

DataTable.use(pdfmake);
DataTable.use(ButtonsHtml5);
DataTable.use(DataTablesCore);

const authUserStore = useAuthUserStore();
const toastr = useToastr();
const pageTitle = `${useRoute().name}`;

const listItem = ref([]);
const columns = [
    {
        data: null,
        render: function (data, type, row, meta) {
            return `${meta.row + 1}`;
        },
    },
    { data: "id" },
    { data: "cuscode" },
    { data: "cusname" },
    { data: "leadtime" },
    { data: "stockfreshness" },
    { data: "is_active" },
    { data: "created_by" },
    {
        data: "id",
        render: "#action",
        title: "Action",
        sortable: false,
    },

];

const botones = [
    {
        title: "CSV",
        extend: "excelHtml5",
        text: '<i class="fa-solid fa-file-excel"></i> Excel',
        className: "btn btn-success",
    },
    {
        title: "Print",
        extend: "excelHtml5",
        text: '<i class="fa-solid fa-file-excel"></i> Print',
        className: "btn btn-success",
    },
];

const isloading = ref(false);
const editing = ref(false);
const formValues = ref();
const checked = ref(true);
const form = reactive({
    id: "",
    warehouse_id: "",
    cuscode: "",
    cusname: "",
    leadtime: "",
    stockfreshness: "",
    is_active: true,
    created_by: "",
});
const createDataSchema = yup.object({
    cuscode: yup.string().required(),
    cusname: yup.string().required(),
    leadtime: yup.string().required(),
    stockfreshness: yup.string().required(),

});

const editDataSchema = yup.object({
    cuscode: yup.string().required(),
    cusname: yup.string().required(),
    leadtime: yup.string().required(),
    stockfreshness: yup.string().required(),
});


const getData = () => {
    axios.get(`/web/customer`).then((response) => {
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
        .post("/web/customer", values)
        .then((response) => {
            if (listItem && listItem.value && listItem.value.data) {
                listItem.value.data.unshift(response.data);
            }
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


const editItem = (id) => {
    alert("Edit item with ID: " + id);
};

const deleteItem = (id) => {
    console.log("Delete item with ID:", id);
};
watch([checked], (val) => {
     form.is_active = val ? 1 : 0;
});
onMounted(() => {
    getData();
    document.title = pageTitle;
});
</script>
<template>
    <div class="content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between">
                <div class="d-flex">
                    <button
                        @click="addItem"
                        type="button"
                        class="mb-2 btn btn-primary"
                    >
                        <i class="fa fa-plus-circle mr-1"></i>
                       {{ pageTitle }}
                    </button>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <DataTable
                            class="mt-2 table table-sm table-hover table-striped display"
                            :columns="columns"
                            :data="listItem"
                            :options="{
                                responsive: false,
                                autoWidth: false,
                                lengthMenu: [
                                    [10, 25, 50, -1],
                                    [10, 25, 50, 'All'],
                                ],
                            }"

                        >
                            <template #action="props">
                                <div>
                                    <Button
                                    class="btn btn-sm btn-primary"
                                    @click="editItem(props.rowData.id)"
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
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Costomer Code</th>
                                    <th>Costomer Name</th>
                                    <th>Leadtime</th>
                                    <th>Stockfreshness</th>
                                    <th>Active</th>
                                    <th>Created by</th>
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
        <div class="modal-dialog modal-sm " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <span v-if="editing">Edit Customer</span>
                        <span v-else>Add Customer</span>
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
                    :initial-values="formValues"
                >
                    <div class="modal-body">
                        <div class="col-md-12">
                                    <Field
                                        type="hidden"
                                        name="created_by"
                                        id="created_by"
                                        v-model="form.created_by"
                                    />



                                    <div class="form-group">
                                        <label for="cuscode"
                                            >Customer Code</label
                                        >
                                        <Field
                                            name="cuscode"
                                            type="text"
                                            class="form-control"
                                            :class="{
                                                'is-invalid': errors.cuscode,
                                            }"
                                            id="cuscode"
                                            aria-describedby="nameHelp"
                                            placeholder="Enter cuscode"
                                            v-model="form.cuscode"
                                        />
                                        <span class="invalid-feedback">{{
                                            errors.cuscode
                                        }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="cusname"
                                            >Costomer Name</label
                                        >
                                        <Field
                                            name="cusname"
                                            type="text"
                                            class="form-control"
                                            :class="{
                                                'is-invalid': errors.cusname,
                                            }"
                                            id="cusname"
                                            aria-describedby="nameHelp"
                                            placeholder="Enter cusname"
                                            v-model="form.cusname"
                                        />
                                        <span class="invalid-feedback">{{
                                            errors.cusname
                                        }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="leadtime">Lead Time</label>
                                        <Field
                                            name="leadtime"
                                            type="text"
                                            class="form-control"
                                            :class="{
                                                'is-invalid': errors.leadtime,
                                            }"
                                            id="leadtime"
                                            aria-describedby="nameHelp"
                                            placeholder="Enter leadtime"
                                            v-model="form.leadtime"
                                        />
                                        <span class="invalid-feedback">{{
                                            errors.leadtime
                                        }}</span>
                                    </div>


                                    <div class="form-group">
                                        <label for="stockfreshness"
                                            >Stock Freshness</label
                                        >
                                        <Field
                                            name="stockfreshness"
                                            type="text"
                                            class="form-control"
                                            id="stockfreshness"
                                            aria-describedby="nameHelp"
                                            placeholder="Enter stockfreshness"
                                            v-model="form.stockfreshness"
                                        />
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
</template>

<style>
@import "datatables.net-bs4";
</style>
