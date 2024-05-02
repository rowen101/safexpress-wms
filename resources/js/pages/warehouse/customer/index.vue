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

const resetForm = () => {
    form.id = "";
    form.warehouse_id = "";
    form.cuscode = "";
    form.cusname = "";
    form.leadtime = "";
    form.stockfreshness = "";
    form.is_active = "";
    form.created_by = "";
    form.contactname = "";
    form.address1 = "";
    form.address2 = "";
    form.town = "";
    form.provinces = "";
    form.country = "";
    form.postal ="";
    form.cellphone = "";
    form.telephone = "";
    form.emailaddress = "";
};
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
    isloading.value = true;
    axios.get(`/web/customer`).then((response) => {
        listItem.value = response.data;
        isloading.value = false;
    });
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
        .post("/web/customer", values)
        .then((response) => {
            $("#FormModal").modal("hide");
            resetForm();
            toastr.success(pageTitle + " " + response.data.message);
        })
        .catch((error) => {
            toastr.error(error.response.data.message);
        })
        .finally(() => {
            getData();
        });
};

const editItem = (item) => {
    // resetForm();
    editing.value = true;
    form.id = item.id;
    form.cuscode = item.cuscode;
    form.cusname = item.cusname;
    form.leadtime = item.leadtime;
    form.stockfreshness = item.stockfreshness;
    form.is_active = item.is_active;
    form.contactname = item.contactname;
    form.address1 = item.address1;
    form.address2 = item.address2;
    form.town = item.town;
    form.provinces = item.provinces;
    form.country = item.country;
    form.emailaddress =item.emailaddress;
    form.telephone = item.telephone;
    form.cellphone = item.cellphone;
    form.postal = item.postal;
    $("#FormModal").modal("show");
};

const updateRecord = ({ setErrors }) => {
    axios
        .post("/web/customer", form)
        .then((response) => {
            getData();
            $("#FormModal").modal("hide");
            toastr.success(pageTitle + " " + response.data.message);
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
            .delete(`/web/customer/${id}`)
            .then((response) => {
                isloading.value = false;
                toastr.success(pageTitle + " " + response.data.message);
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
                    <ContentLoader v-if="isloading" />


                        <DataTable
                        style="width:100%"
                            class="mt-2 table table-sm table-hover table-striped display nowrap"
                            :columns="columns"
                            :data="listItem"
                            :options="{
                                responsive: true,
                                rowReorder: {
                                    selector: 'td:nth-child(2)'
                                },
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

    <div
        class="modal fade"
        id="FormModal"
        data-backdrop="static"
        tabindex="1"
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
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="leadtime">Lead Time</label>
                                        <div class="input-group">
                                            <Field
                                                name="leadtime"
                                                type="text"
                                                class="form-control"
                                                :class="{
                                                    'is-invalid':
                                                        errors.leadtime,
                                                }"
                                                id="leadtime"
                                                aria-describedby="nameHelp"
                                                placeholder="Enter leadtime"
                                                v-model="form.leadtime"
                                            />

                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    days
                                                </span>
                                            </div>
                                        </div>

                                        <span class="invalid-feedback">{{
                                            errors.leadtime
                                        }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="stockfreshness"
                                            >Stock Freshness</label
                                        >
                                        <div class="input-group">
                                            <Field
                                                name="stockfreshness"
                                                type="text"
                                                class="form-control"
                                                id="stockfreshness"
                                                aria-describedby="nameHelp"
                                                placeholder="Enter stockfreshness"
                                                v-model="form.stockfreshness"
                                            />

                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    %
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <fieldset>
                                <legend>Contact info</legend>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="contactname"
                                                >Contact Person</label
                                            >

                                            <Field
                                                name="contactname"
                                                type="text"
                                                class="form-control"
                                                id="contactname"
                                                aria-describedby="nameHelp"
                                                placeholder="Enter Contact Name"
                                                v-model="form.contactname"
                                            />
                                        </div>
                                        <div class="form-group">
                                            <label for="cellphone"
                                                >Celphone No.</label
                                            >

                                            <Field
                                                name="cellphone"
                                                type="text"
                                                class="form-control"
                                                id="cellphone"
                                                aria-describedby="nameHelp"
                                                placeholder="Enter Cellphone No"
                                                v-model="form.cellphone"
                                            />
                                        </div>
                                        <div class="form-group">
                                            <label for="telephone"
                                                >Telephone No.</label
                                            >

                                            <Field
                                                name="telephone"
                                                type="text"
                                                class="form-control"
                                                id="telephone"
                                                aria-describedby="nameHelp"
                                                placeholder="Enter telephone"
                                                v-model="form.telephone"
                                            />
                                        </div>
                                        <div class="form-group">
                                            <label for="emailaddress"
                                                >Email</label
                                            >

                                            <Field
                                                name="emailaddress"
                                                type="text"
                                                class="form-control"
                                                id="emailaddress"
                                                aria-describedby="nameHelp"
                                                placeholder="Enter Email Address"
                                                v-model="form.emailaddress"
                                            />
                                        </div>

                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="address1"
                                                >Address1:</label
                                            >

                                            <Field
                                                name="address1"
                                                type="text"
                                                class="form-control"
                                                id="address1"
                                                aria-describedby="nameHelp"
                                                placeholder="Enter Address1"
                                                v-model="form.address1"
                                            />
                                        </div>
                                        <div class="form-group">
                                            <label for="address2"
                                                >Address2:</label
                                            >

                                            <Field
                                                name="address2"
                                                type="text"
                                                class="form-control"
                                                id="address2"
                                                aria-describedby="nameHelp"
                                                placeholder="Enter Address2"
                                                v-model="form.address2"
                                            />
                                        </div>
                                        <div class="form-group">
                                            <label for="town"
                                                >Town:</label
                                            >

                                            <Field
                                                name="town"
                                                type="text"
                                                class="form-control"
                                                id="town"
                                                aria-describedby="nameHelp"
                                                placeholder="Enter town"
                                                v-model="form.town"
                                            />
                                        </div>
                                        <div class="form-group">
                                            <label for="province"
                                                >Province:</label
                                            >

                                            <Field
                                                name="province"
                                                type="text"
                                                class="form-control"
                                                id="province"
                                                aria-describedby="nameHelp"
                                                placeholder="Enter province"
                                                v-model="form.province"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
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
