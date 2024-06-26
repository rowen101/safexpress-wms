<script setup>
import axios from "axios";
import { ref, onMounted, reactive, watch, inject } from "vue";
import { Form, Field, useResetForm } from "vee-validate";
import * as yup from "yup";
import { useToastr } from "@/toastr";
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
import { useRoute } from "vue-router";
import ContentLoader from "../../../components/ContentLoader.vue";

DataTable.use(pdfmake);
DataTable.use(ButtonsHtml5);
DataTable.use(DataTablesCore);
const swal = inject("$swal");
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
    { data: "supcode" },
    { data: "supname" },
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
const checked = ref(true);
const form = reactive({
    id: "",
    warehouse_id: "",
    supcode: "",
    supname: "",
    is_active: true,
    created_by: "",
});

const resetForm = () => {
    form.id = "";
    form.warehouse_id = "";
    form.supcode = "";
    form.supname = "";
    form.is_active = '';
    form.created_by = "";
};
const createDataSchema = yup.object({
    supcode: yup.string().required(),
    supname: yup.string().required(),
});

const editDataSchema = yup.object({
    supcode: yup.string().required(),
    supname: yup.string().required(),
});

const getData = () => {
    isloading.value = true;
    axios.get(`/web/supplier`).then((response) => {
        listItem.value = response.data;
        isloading.value = false;
    })

};
const addItem = () => {
    resetForm();
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
        .post("/web/supplier", values)
        .then((response) => {


            $("#FormModal").modal("hide");
            resetForm();
            toastr.success(pageTitle+" "+response.data.message);
        })
        .catch((error) => {
                toastr.error(error.response.data.message);
        })
        .finally(() => {
            getData();

        });
};

const editItem = (item) => {
   resetForm();
    editing.value = true;
    form.id = item.id;
    form.supcode = item.supcode;
    form.supname = item.supname;
    form.is_active = item.is_active;
    $("#FormModal").modal("show");
};

const updateRecord = ({ setErrors }) => {
    axios
        .post("/web/supplier", form)
        .then((response) => {
            getData();
            $("#FormModal").modal("hide");
            toastr.success(pageTitle+" "+response.data.message);
        })
        .catch((error) => {
            setErrors(error.response.data.errors);
            console.log(error);
        });

};
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
            .delete(`/web/supplier/${id}`)
            .then((response) => {
                isloading.value = false;
                toastr.success(pageTitle+' '+response.data.message);
                getData();
            })
            .catch((error) => {
                console.log(error);
            });
    }
};

onMounted(() => {
    getData();
    document.title = pageTitle;
});
</script>
<template>
    <div class="content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between">
                <div class="d-flex"></div>
            </div>
            <div class="card">
                <div class="card-body">
                    <ContentLoader v-if="isloading"/>

                    <div v-else class="table-responsive">
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
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Supplier Code</th>
                                    <th>Supplier Name</th>
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
        tabindex="1"
        role="dialog"
        aria-labelledby="staticBackdropLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-sm" role="document">
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
                            <Field
                                type="hidden"
                                name="created_by"
                                id="created_by"
                                v-model="form.created_by"
                            />

                            <div class="form-group">
                                <label for="supcode">Supplier Code</label>
                                <Field
                                    name="supcode"
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': errors.supcode,
                                    }"
                                    id="supcode"
                                    aria-describedby="nameHelp"
                                    placeholder="Enter Supplier Code"
                                    v-model="form.supcode"
                                />
                                <span class="invalid-feedback">{{
                                    errors.supcode
                                }}</span>
                            </div>

                            <div class="form-group">
                                <label for="supname">Supplier Name</label>
                                <Field
                                    name="supname"
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': errors.supname,
                                    }"
                                    id="supname"
                                    aria-describedby="nameHelp"
                                    placeholder="Enter Supplier Name"
                                    v-model="form.supname"
                                />
                                <span class="invalid-feedback">{{
                                    errors.supname
                                }}</span>
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
