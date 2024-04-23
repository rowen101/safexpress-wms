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
    { data: "truckercode" },
    { data: "truckername" },
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
    truckercode: "",
    truckername: "",
    is_active: true,
});
const createDataSchema = yup.object({
    truckercode: yup.string().required(),
    truckername: yup.string().required(),
});

const editDataSchema = yup.object({
    truckercode: yup.string().required(),
    truckername: yup.string().required(),
});
const getItems = () => {
    isLoadingSite.value = true;
    axios
        .get(`/web/trucker`)
        .then((response) => {
            isLoadingSite.value = false;
            listItem.value = response.data;
        })
        .catch((error) => {
            console.log(error);
        });
};
const getData = () => {
    axios.get(`/web/trucker`).then((response) => {
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
        .post("/web/trucker", values)
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
        .post("/web/trucker", form)
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
    form.truckercode = item.truckercode;
    form.truckername = item.truckername;
    form.is_active = item.is_active;
    $("#FormModal").modal("show");
};

const deleteItem = (id) => {
    isLoadingSite.value = true;
    axios
        .delete(`/web/trucker/${id}`)
        .then((response) => {
            isLoadingSite.value = false;
            getData();
        })
        .catch((error) => {
            console.log(error);
        });
};
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
                                    <th>Trucker Code</th>
                                    <th>Trucker Name</th>
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
                                <label for="truckercode">Trucker Code</label>
                                <Field
                                    name="truckercode"
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': errors.truckercode,
                                    }"
                                    id="truckercode"
                                    aria-describedby="nameHelp"
                                    placeholder="Enter Trucker Code"
                                    v-model="form.truckercode"
                                />
                                <span class="invalid-feedback">{{
                                    errors.truckercode
                                }}</span>
                            </div>

                            <div class="form-group">
                                <label for="truckername">Trucker Name</label>
                                <Field
                                    name="truckername"
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': errors.truckername,
                                    }"
                                    id="truckername"
                                    aria-describedby="nameHelp"
                                    placeholder="Enter Trucker Name"
                                    v-model="form.truckername"
                                />
                                <span class="invalid-feedback">{{
                                    errors.truckname
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
