<template>
    <div class="">
        <h1 class="mb-3 text-90 font-normal text-2xl" v-if="step == 1">
            {{ __('Create new Resource') }}
        </h1>

        <h1 class="mb-3 text-90 font-normal text-2xl" v-if="step == 2">
            {{ __('Creating new Resource') }} 
        </h1>

        <div class="card" v-if="step == 2">
            <div class="flex flex-wrap border-b border-40 mb-6">

                <div class="flex flex-wrap mr-4">
                    <div class="px-8 py-6">
                        <label class="inline-block text-80 pt-2 leading-tight pr-2">
                            {{ __('Table') }}:
                        </label>
                        <div class="inline-block text-80 pt-2 leading-tight">
                            {{ selected_table }}
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap">
                    <div class="px-8 py-6">
                        <label class="inline-block text-80 pt-2 leading-tight pr-2">
                            {{ __('Resource name') }}:
                        </label>
                        {{ singular }}
                    </div>
                </div>
            </div>
        </div>

        <h1 class="mb-3 text-90 font-normal text-2xl" v-if="step == 2">
            {{ __('Fields') }} 
        </h1>


        <div class="card">
            <div class="flex flex-wrap border-b border-40">
                <template  v-if="step == 1">
                    <div class="w-full flex flex-wrap">
                        <div class="w-1/5 px-8 py-6">
                            <label class="inline-block text-80 pt-2 leading-tight" for="table">
                                {{ __('Choose Table') }}
                            </label>
                        </div>
                        <div class="w-1/2 px-8 py-6">
                            <select class="w-full form-control form-select" id="table" v-model="selected_table" @change="changeTable">
                                <option disabled="disabled" selected="selected" :value="null">
                                    {{ __('Choose a table') }}
                                </option>
                                <option v-for="info in tables" :value="info.table" :key="info.table">{{ info.table }}</option>
                            </select>
                            <!-- -->
                            <p class="my-2 text-danger" v-if="table_error">{{ __('Please, choose a table') }}</p>
                        </div>
                    </div>

                    <div class="w-full" v-if="selected_table">

                        <!-- Name -->
                        <div class="w-full  flex flex-wrap">
                            <div class="w-1/5 px-8 py-6">
                                <label class="inline-block text-80 pt-2 leading-tight" for="resource_name">
                                    {{ __('Resource name') }}
                                </label>
                            </div>
                            <div class="w-1/2 px-8 py-6">
                                <input class="w-full form-control form-input" id="resource_name" v-model="singular">
                                <!-- -->
                                <p class="my-2 text-danger" v-if="singular.length == 0">{{ __('Please, add a Resource name') }}</p>
                            </div>
                        </div>

                        <!-- Title -->
                        <div class="w-full  flex flex-wrap">
                            <div class="w-1/5 px-8 py-6">
                                <label class="inline-block text-80 pt-2 leading-tight" for="resource_name">
                                    {{ __('Title field') }}
                                </label>
                            </div>
                            <div class="w-1/2 px-8 py-6">
                                <select class="w-full form-control form-select" id="table" v-model="title">
                                    <option disabled="disabled" selected="selected" :value="null">
                                        {{ __('Choose a column') }}
                                    </option>

                                    <option v-for="columnNames in columns" :value="columnNames.name" :key="columnNames.name">{{ columnNames.name }}</option>
                                </select>
                                <!-- -->
                                <p class="my-2 text-danger" v-if="title_error">{{ __('You need to choose a column') }}</p>
                            </div>
                        </div>

                        <!-- Search -->
                        <div class="w-full  flex flex-wrap">
                            <div class="w-1/5 px-8 py-6">
                                <label class="inline-block text-80 pt-2 leading-tight" for="resource_name">
                                    {{ __('Search fields') }}
                                </label>
                            </div>
                            <div class="w-1/2 px-8 py-6">
                               

                                <multiselect v-show="fieldsObject.length > 0" v-model="search" :options="fieldsObject" :multiple="true" :searchable="true" :close-on-select="false" :show-labels="false" :placeholder="__('Choose field/s')"></multiselect>

                                <!-- -->
                                <p class="my-2 text-danger" v-if="title_error">{{ __('You need to choose a column') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="w-full flex flex-wrap">
                        <div class="w-1/5 px-8 py-6">
                            <label class="inline-block text-80 pt-2 leading-tight" for="generate_model">
                                {{ __('Create Model') }}
                            </label>
                        </div>
                        <div class="w-1/2 px-8 py-6">
                            <input type="checkbox" id="generate_model" v-model="create_model">
                        </div>
                    </div>
                </template>
                <template  v-if="step == 2">



                    <headerColumns :hasExtras="hasExtrasColumns" />
                    <template v-for="(column, index) in columns">
                        <column
                            :key="column.name"
                            :ref="column.name"
                            :id="selected_table + '_'+column.name"
                            :column="column"
                            :tables="tables"
                            :settings="settings"
                            :hasExtraColumn="hasExtrasColumns"
                            :countries="countries"
                            :v="$v.columns.$each.$iter[index]" />
                    </template>
                </template>

                <template  v-if="step == 3">
                    <div class="w-full flex flex-wrap text-center justify-center items-center" style="height: 400px">
                        <div class="">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                                <circle class="path circle" fill="none" stroke="#73AF55" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
                                <polyline class="path check" fill="none" stroke="#73AF55" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/>
                            </svg>
                            <h2 class="w-full my-3 text-success">{{ __('Resource generated!') }}</h2>
                            <p>{{ __('Reloading page in 3 seconds') }}</p>
                        </div>
                    </div>
                </template>
            </div>
        </div>
        <div class="bg-30 flex px-8 py-4">
            <button class="ml-auto btn btn-default btn-primary mr-3" dusk="create-and-add-another-button" type="button" v-if="step == 1" @click="goToFields">
                {{ __('Continue') }}
            </button>

            <div class="ml-auto" v-if="step == 2">
                <button class="btn text-80 font-normal h-9 px-3 mr-3 btn-link" type="button" @click="restartCheck" v-if="!restart_button">
                    {{ __('Restart') }}
                </button>

                <button class="btn text-80 font-normal h-9 px-3 mr-3 btn-link" type="button" @click="restart"  v-if="restart_button">
                    {{ __('Are you sure?') }}
                </button>


                <button class="btn btn-default btn-primary mr-3" dusk="create-and-add-another-button" type="button"  @click="generateResources">
                    {{ __('Generate Resources') }}
                </button>
            </div>
        </div>
        <div ref="modals">
            <modal ref="modalConfirm" v-if="modalConfirm" :name="'modalConfirm'" :align="'flex justify-end'">
                <div slot="container">
                    <h2 class="mb-6 text-90 font-normal text-xl">Attention!</h2>
                    <p class="text-80 leading-normal ">
                        {{ confirmText }}
                    </p>
                </div>
                <div slot="buttons">
                    <div class="ml-auto">
                        <button type="button" @click.prevent="modalConfirm = !modalConfirm" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">
                            {{ __('Cancel') }}
                        </button>

                        <button id="confirm-overwrite-button" ref="confirmButton"  @click.prevent="confirmOverwrite" class="btn btn-default btn-danger">
                            {{ __('Overwrite!') }}
                        </button>
                    </div>
                </div>
            </modal>
        </div>
    </div>
</template>
<script>
import _ from 'lodash';
import api from '../api';
import HeaderColumns from './HeaderColumns.vue';
import column from './column.vue';
import Multiselect from 'vue-multiselect';
import { validationMixin } from 'vuelidate';
import { required, requiredIf } from 'vuelidate/lib/validators';
import Modal from './Modal';

var countriesApi = require('country-list')();

export default {
    components: {
        column: column,
        headerColumns: HeaderColumns,
        multiselect: Multiselect,
        modal: Modal,
    },

    mixins: [validationMixin],

    data: () => ({
        step: 1,
        settings: {},
        selected_table: null,
        tables: [],
        singular: null,
        table_error: false,
        columns: {},
        create_model: false,
        title: 'id',
        title_error: false,
        search: ['id'],
        hasExtrasColumns: false,
        restart_button: false,
        modalConfirm: false,
        confirmText: null,
        countries: [],
    }),

    validations: {
        columns: {
            $each: {
                label: {
                    required,
                },
                field: {
                    required,
                },
                relation: {
                    required: requiredIf(function(nestedModel) {
                        return nestedModel.field == 'Relation' ? true : false;
                    }),
                },
                related_table: {
                    required: requiredIf(function(nestedModel) {
                        return nestedModel.field == 'Relation' ? true : false;
                    }),
                },
                related_column: {
                    required: requiredIf(function(nestedModel) {
                        return nestedModel.field == 'Relation' ? true : false;
                    }),
                },
            },
        },
    },

    methods: {
        getData() {
            api.getTables().then(result => {
                this.tables = result.tables;
                this.settings = result.settings;

                if (this.settings.model.value == true) {
                    this.create_model = true;
                }
            });
        },

        getColumns() {
            api.getColumns(this.selected_table).then(result => {
                this.columns = result.columns;
            });
        },

        changeTable() {
            let find = _.find(this.tables, ['table', this.selected_table]);
            if (find) {
                this.singular = find.singular;
            }
            this.table_error = false;
            this.getColumns();
        },

        goToFields() {
            if (!this.selected_table) {
                this.table_error = true;
                return false;
            }

            if (this.singular.length === 0) {
                return false;
            }

            this.checkFilesExists().then(canPass => {
                if (canPass) {
                    if (this.columns.length === 0) {
                        this.getColumns();
                    }

                    this.step = 2;
                }
            });
        },

        confirmOverwrite() {
            this.modalConfirm = false;

            if (this.columns.length === 0) {
                this.getColumns();
            }

            this.step = 2;
        },

        checkFilesExists() {
            return api
                .checkFiles(this.create_model, this.singular)
                .then(response => {
                    if (this.create_model) {
                        if (response.model === true && response.resource === true) {
                            this.confirmText =
                                'The Model and Resource files exists. Do you want to overwrite?';
                            this.modalConfirm = true;
                            return false;
                        }

                        if (response.model === true) {
                            this.confirmText = 'The Model file exists. Do you want to overwrite?';
                            this.modalConfirm = true;
                            return false;
                        }
                    }

                    if (response.resource === true) {
                        this.confirmText = 'The Resource file exists. Do you want to overwrite?';
                        this.modalConfirm = true;
                        return false;
                    }

                    return true;
                })
                .catch(() => {});
        },

        generateResources() {
            this.$v.$touch();

            if (this.$v.$invalid) {
                this.$toasted.show(
                    this.__('Please check all data. You have some mandatory field'),
                    { type: 'error' }
                );
                return false;
            }

            return api
                .generateResources(
                    this.selected_table,
                    this.singular,
                    this.title,
                    this.search,
                    this.create_model,
                    this.columns
                )
                .then(() => {
                    this.step = 3;
                    this.reset();

                    setTimeout(() => {
                        location.reload();
                    }, 4000);
                });
        },

        restartCheck() {
            this.restart_button = true;

            setTimeout(() => {
                this.restart_button = false;
            }, 3000);
        },

        restart() {
            this.step = 1;
            this.reset();
        },

        reset() {
            this.selected_table = null;
            this.singular = null;
            this.table_error = false;
            this.columns = {};
            this.create_model = false;
            this.title_error = false;
            this.hasRelationColumns = false;
            this.hasExtrasColumns = false;
        },
    },

    computed: {
        fieldsObject: function() {
            if (_.size(this.columns) > 0) {
                return _.toArray(
                    _.mapValues(this.columns, function(o) {
                        return o.name;
                    })
                );
            }

            return [];
        },
    },

    watch: {
        columns: {
            handler: function() {
                let passExtras = false;
                _.forEach(this.columns, column => {
                    if (column.field == 'Relation' && column.show != 'disabled') {
                        passExtras = true;
                        this.hasExtrasColumns = true;
                    }
                    if (
                        column.field == 'Select' &&
                        column.show != 'disabled' &&
                        passExtras === false
                    ) {
                        passExtras = true;
                        this.hasExtrasColumns = true;
                    }
                    if (
                        column.field == 'Code' &&
                        column.show != 'disabled' &&
                        passExtras === false
                    ) {
                        passExtras = true;
                        this.hasExtrasColumns = true;
                    }

                    if (
                        column.field == 'Boolean' &&
                        column.show != 'disabled' &&
                        passExtras === false
                    ) {
                        passExtras = true;
                        this.hasExtrasColumns = true;
                    }

                    if (
                        column.field == 'Currency' &&
                        column.show != 'disabled' &&
                        passExtras === false
                    ) {
                        passExtras = true;
                        this.hasExtrasColumns = true;
                    }

                    if (
                        column.field == 'Date' &&
                        column.show != 'disabled' &&
                        passExtras === false
                    ) {
                        passExtras = true;
                        this.hasExtrasColumns = true;
                    }

                    if (
                        column.field == 'DateTime' &&
                        column.show != 'disabled' &&
                        passExtras === false
                    ) {
                        passExtras = true;
                        this.hasExtrasColumns = true;
                    }

                    if (
                        column.field == 'File' &&
                        column.show != 'disabled' &&
                        passExtras === false
                    ) {
                        passExtras = true;
                        this.hasExtrasColumns = true;
                    }

                    if (
                        column.field == 'Image' &&
                        column.show != 'disabled' &&
                        passExtras === false
                    ) {
                        passExtras = true;
                        this.hasExtrasColumns = true;
                    }

                    if (
                        column.field == 'Avatar' &&
                        column.show != 'disabled' &&
                        passExtras === false
                    ) {
                        passExtras = true;
                        this.hasExtrasColumns = true;
                    }

                    if (
                        column.field == 'Trix' &&
                        column.show != 'disabled' &&
                        passExtras === false
                    ) {
                        passExtras = true;
                        this.hasExtrasColumns = true;
                    }

                    if (
                        column.field == 'Number' &&
                        column.show != 'disabled' &&
                        passExtras === false
                    ) {
                        passExtras = true;
                        this.hasExtrasColumns = true;
                    }

                    if (
                        column.field == 'Place' &&
                        column.show != 'disabled' &&
                        passExtras === false
                    ) {
                        passExtras = true;
                        this.hasExtrasColumns = true;
                    }

                    if (
                        column.field == 'Status' &&
                        column.show != 'disabled' &&
                        passExtras === false
                    ) {
                        passExtras = true;
                        this.hasExtrasColumns = true;
                    }
                });

                if (!passExtras) {
                    this.hasExtrasColumns = false;
                }
            },
            deep: true,
        },
    },

    async created() {
        await this.getData();
        this.countries = countriesApi.getData();
    },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css">
</style>
<style scoped>
.multiselect {
    border-color: var(--60);
    border-width: 1px;
    border-radius: 0.5rem;
}

svg {
    width: 100px;
    display: block;
    margin: 40px auto 0;
}
.path {
    stroke-dasharray: 1000;
    stroke-dashoffset: 0;
}
.path.circle {
    -webkit-animation: dash 0.9s ease-in-out;
    animation: dash 0.9s ease-in-out;
}
.path.line {
    stroke-dashoffset: 1000;
    -webkit-animation: dash 0.9s 0.35s ease-in-out forwards;
    animation: dash 0.9s 0.35s ease-in-out forwards;
}
.path.check {
    stroke-dashoffset: -100;
    -webkit-animation: dash-check 0.9s 0.35s ease-in-out forwards;
    animation: dash-check 0.9s 0.35s ease-in-out forwards;
}
/*p {
    text-align: center;
    margin: 20px 0 60px;
    font-size: 1.25em;
}*/
p.success {
    color: #73af55;
}
p.error {
    color: #d06079;
}

.modal {
    p {
        color: var(--80);
        margin: 0;
        text-align: left;
    }
}
@-webkit-keyframes dash {
    0% {
        stroke-dashoffset: 1000;
    }
    100% {
        stroke-dashoffset: 0;
    }
}
@keyframes dash {
    0% {
        stroke-dashoffset: 1000;
    }
    100% {
        stroke-dashoffset: 0;
    }
}
@-webkit-keyframes dash-check {
    0% {
        stroke-dashoffset: -100;
    }
    100% {
        stroke-dashoffset: 900;
    }
}
@keyframes dash-check {
    0% {
        stroke-dashoffset: -100;
    }
    100% {
        stroke-dashoffset: 900;
    }
}
</style>
