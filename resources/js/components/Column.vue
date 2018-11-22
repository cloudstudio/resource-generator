<template>
    <div class="w-full flex flex-wrap justify-around text-left" v-if="shouldShow">

        <!-- Column label name -->
        <div class="px-3 column">
            <input class="w-full form-control form-input form-input-bordered py-3 px-4" :class="{'border-danger': v.label.$error}" type="text" :id="id+'_label'" v-model="column.label" autocomplete="off">
        </div>

        <!-- Column field type -->
        <div class="px-3 column">
            <select class="w-full form-control form-input form-input-bordered px-4" :class="{'border-danger': v.field.$error}" :id="id+'_type'" v-model="column.field" @change="checkField">
                <option v-for="(field, key) in fields" :value="field.name" :key="key">
                    {{ field.name }}
                </option>
            </select>



            <modal ref="modalRelations" v-if="modalRelations" :width="800" :align="'flex justify-end'">
                <div slot="container">

                    <div class="w-full flex flex-wrap">

                        <div class="w-1/3 pr-3">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2"  :for="id+'_relation'">
                                {{ __('Relation type') }}
                            </label>

                            <select class="w-full form-control form-input form-input-bordered px-4" :class="{'border-danger': v.relation.$error}" :id="id+'_relation'" v-model="column.relation" :disabled="!hasRelation">
                            
                                <option v-if="hasRelation" :value="null">Choose a relation</option>
                                <option v-else :value="null">No relation needed</option>


                                <option v-for="(relation, key) in columnRelations" :value="key" :key="key">
                                    {{ relation }}
                                </option>
                            </select>
                        </div>

                        <div class="w-1/3 pr-3">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" :for="id+'_relation_table'">
                                {{ __('Related table') }}
                            </label>
                            <select class="w-full form-control form-select" :id="id+'_relation_table'" :class="{'border-danger': v.related_table.$error}" v-model="column.related_table" @change="changeTableRelated">
                                <option disabled="disabled" selected="selected" :value="null">
                                    {{ __('Choose a table') }}
                                </option>
                                <option v-for="info in tables" :value="info.table" :key="info.table">{{ info.table }}</option>
                            </select>
                        </div>

                        <div class="w-1/3 pr-3">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" :for="id+'_relation_column'">
                                {{ __('Related column') }}
                            </label>
                            <select class="w-full form-control form-select" :id="id+'_relation_column'" :class="{'border-danger': v.related_column.$error}" v-model="column.related_column">
                                <option disabled="disabled" selected="selected" :value="null">
                                    {{ __('Choose a column') }}
                                </option>
                                <option v-for="info in related_columns" :value="info.name" :key="info.name">{{ info.name }}</option>
                            </select>
                        </div>

                    </div>
                    <div class="w-full flex flex-wrap mt-6" v-if="column.relation == 'belongsTo'">
                        <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mr-2" :for="id+'_relation_column_searchable'">
                            {{ __('Searchable') }}
                        </label>
                        <input type="checkbox" :id="id+'_relation_column_searchable'" v-model="column.searchable">
                    </div>
                </div>

                <div slot="buttons">
                    <button type="button" data-testid="cancel-button" @click="cancelRelation" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{ __('Cancel') }}</button>
                    <button class="ml-auto btn btn-default btn-primary mr-3" v-on:click="modalRelations = false">{{ __('Set') }}</button>
                </div>
            </modal>

        </div>


        <!-- Column Require -->
        <div class="px-3 column">
            <input type="checkbox" :id="id+'_required'" v-model="column.required">
        </div>

    

        <!-- Column Sortable -->
        <div class="px-3 column">
            <input type="checkbox" :id="id+'_sortable'" v-model="column.sortable">
        </div>

        <!-- Column Show In -->
        <div class="px-3 column">
            <select class="w-full form-control form-input form-input-bordered px-4" :id="id+'_show'" v-model="column.show">
                <option v-for="(showText, key) in selectShow" :value="key" :key="key">
                    {{ showText }}
                </option>
            </select>
        </div>

        <!-- Test -->
        <div class="px-3 column" v-if="hasExtraColumn">
            <template  v-if="columnHasExtra">
                <button class="ml-auto btn btn-default btn-primary mr-3"  @click="showExtrasModal">Set Extras</button>
            </template>

            <modal ref="modalExtras" v-if="modalExtras"  :width="extraPopupWidth">
                <div slot="container">

                    <template  v-if="column.field == 'Select'">
                        <div class="flex flex-wrap " >
                            <div class="w-1/2 flex flex-wrap content-start">
                                <div class="w-1/2 pr-3">
                                    <input class="w-full form-control form-input form-input-bordered py-3 px-4" :placeholder="__('Key')" type="text" v-model="select_key" autocomplete="off">
                                </div>

                                <div class="w-1/2 pr-3">
                                    <input class="w-full form-control form-input form-input-bordered py-3 px-4" :placeholder="__('Option')" type="text" v-model="select_option" autocomplete="off">
                                </div>
                                
                                <div class="w-full pt-3"> 
                                    <button class="mx-auto btn btn-default btn-primary mr-3" @click="addOption">{{ __('Add Option') }}</button>
                                </div>
                            </div>

                            <div class="w-1/2 flex flex-wrap">
                                <table class="table w-full">
                                    <thead>
                                        <tr>
                                            <th class="text-left">{{ __('Key') }}</th>
                                            <th class="text-left">{{ __('Option') }}</th>
                                            <th class="text-left">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current"><path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path></svg>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(option, key) in column.options" :key="key">
                                            <td>{{ key }}</td>
                                            <td>{{ option }}</td>
                                            <td>
                                                <button title="Delete" class="appearance-none cursor-pointer text-70 hover:text-primary mr-3" @click="removeOption(key)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current"><path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </template>

                    <template  v-if="column.field == 'Code'">
                        <div class="flex flex-wrap " >
                            <div class="w-full flex flex-wrap content-start">
                                <div class="w-full pr-3">
                                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" :for="id+'_code_language'">
                                        {{ __('Language or Json') }}
                                    </label>
                                    <select class="w-full form-control form-select" :id="id+'_code_language'" v-model="column.language">
                                        <option disabled="disabled" selected="selected" :value="null">
                                            {{ __('Choose an option') }}
                                        </option>
                                        <option v-for="(language, key) in codeSelect" :value="key" :key="key">{{ language }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template  v-if="column.field == 'Boolean'">
                        <div class="flex flex-wrap " >
                            <div class="w-full flex flex-wrap content-start">
                                <div class="w-1/2 pr-3">
                                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" :for="id+'_boolean_true'">
                                        {{ __('True value') }}
                                    </label>
                                    <input class="w-full form-control form-input form-input-bordered py-3 px-4" id="id+'_boolean_true'" :placeholder="__('True value')" type="text" v-model="column.trueValue" autocomplete="off">
                                </div>
                                <div class="w-1/2 pr-3">
                                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" :for="id+'_boolean_false'">
                                        {{ __('False value') }}
                                    </label>
                                    <input class="w-full form-control form-input form-input-bordered py-3 px-4" id="id+'_boolean_false'" :placeholder="__('False value')" type="text" v-model="column.falseValue" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </template>

                    <template  v-if="column.field == 'Currency' || column.field == 'Date' || column.field == 'DateTime'">
                        <div class="flex flex-wrap " >
                            <div class="w-full flex flex-wrap content-start">
                                <div class="w-full pr-3">
                                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" :for="id+'_column_format'">
                                        {{ __('Format') }}
                                    </label>
                                    <input class="w-full form-control form-input form-input-bordered py-3 px-4" id="id+'_column_format'" :placeholder="__('format')" type="text" v-model="column.format" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </template>
                    

                    <template  v-if="column.field == 'File' || column.field == 'Image' || column.field == 'Avatar'">
                        <div class="flex flex-wrap " >
                            <div class="w-full flex flex-wrap content-start">
                                <div class="w-full pr-3">
                                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" :for="id+'_column_disk'">
                                        {{ __('Disk') }}
                                    </label>
                                    <input class="w-full form-control form-input form-input-bordered py-3 px-4" id="id+'_column_disk'" :placeholder="__('disk')" type="text" v-model="column.disk" autocomplete="off">
                                </div>
                                <div class="w-full flex flex-wrap mt-6">
                                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mr-2" :for="id+'_prunable_column'">
                                        {{ __('Prunable') }}
                                    </label>
                                    <input type="checkbox" :id="id+'_prunable_column'" v-model="column.prunable">
                                </div>
                            </div>
                        </div>
                    </template>

                    <template  v-if="column.field == 'Trix'">
                        <div class="flex flex-wrap " >
                            <div class="w-full flex flex-wrap content-start">
                                <div class="w-full pr-3">
                                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" :for="id+'_column_disk'">
                                        {{ __('Disk') }}
                                    </label>
                                    <input class="w-full form-control form-input form-input-bordered py-3 px-4" id="id+'_column_disk'" :placeholder="__('disk')" type="text" v-model="column.disk" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </template>

                    <template  v-if="column.field == 'Number'">
                        <div class="flex flex-wrap " >
                            <div class="w-full flex flex-wrap content-start">
                                <div class="w-1/3 pr-3">
                                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" :for="id+'_column_min'">
                                        {{ __('Min') }}
                                    </label>
                                    <input class="w-full form-control form-input form-input-bordered py-3 px-4" id="id+'_column_min'" :placeholder="__('min')" type="number" step=".01" v-model="column.min" autocomplete="off">
                                </div>
                                <div class="w-1/3 pr-3">
                                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" :for="id+'_column_max'">
                                        {{ __('Max') }}
                                    </label>
                                    <input class="w-full form-control form-input form-input-bordered py-3 px-4" id="id+'_column_max'" :placeholder="__('max')" type="number" step=".01" v-model="column.max" autocomplete="off">
                                </div>
                                <div class="w-1/3 pr-3">
                                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" :for="id+'_column_step'">
                                        {{ __('Step') }}
                                    </label>
                                    <input class="w-full form-control form-input form-input-bordered py-3 px-4" id="id+'_column_step'" :placeholder="__('step')" type="number" step=".01" v-model="column.step" autocomplete="off">
                                </div>
                            </div>
                            <div class="w-full pt-2">
                                <small>{{ __('Info: Empty values will omit the extra in the resource') }}</small>
                            </div>
                        </div>
                    </template>


                    <template  v-if="column.field == 'Place'">
                        <div class="flex flex-wrap " >
                            <div class="w-full flex flex-wrap content-start">
                                <div class="w-full flex flex-wrap mt-6">
                                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mr-2" :for="id+'_column_countries'">
                                        {{ __('Countries') }}
                                    </label>
                                    <multiselect 
                                        v-model="selectedCities"
                                        :options="countries"
                                        :label="'name'"
                                        :trackBy="'code'"
                                        :multiple="true"
                                        :searchable="true"
                                        :close-on-select="true"
                                        :show-labels="false"
                                        :placeholder="__('Choose citie/s or left blank')">
                                    </multiselect>
                                </div>

                                <div class="w-full flex flex-wrap mt-6">
                                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mr-2" :for="id+'_column_only_cities'">
                                        {{ __('Only Cities') }}
                                    </label>
                                    <input type="checkbox" :id="id+'_column_only_cities'" v-model="column.onlyCities">
                                </div>
                            </div>
                        </div>
                    </template>

                    <template  v-if="column.field == 'Status'">
                        <div class="flex flex-wrap " >
                            <div class="w-full flex flex-wrap content-start">
                                <div class="w-full flex flex-wrap mt-6">
                                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mr-2" :for="id+'_column_loadingWhen'">
                                        {{ __('Loading When') }}
                                    </label>
                                    <multiselect v-model="column.loadingWhen" :taggable="true" :options="['waiting', 'running']" :multiple="true" @tag="addLoading"></multiselect>
                                </div>
                                <div class="w-full flex flex-wrap mt-6">
                                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mr-2" :for="id+'_column_failedWhen'">
                                        {{ __('Failed When') }}
                                    </label>
                                    <multiselect v-model="column.failedWhen" :taggable="true" :options="['failed']" :multiple="true" @tag="addFailed"></multiselect>
                                </div>
                            </div>
                        </div>
                    </template>


                </div>
                <div slot="buttons">
                    <div class="w-full flex flex-wrap">
                        <div class="ml-0 flex items-center">
                            <small>
                                <a v-if="referenceUrl" class="refs" target="_blank" :href="referenceUrl">
                                    {{ __('Laravel Nova docs reference') }}
                                </a>
                            </small>
                        </div>

                        <button class="ml-auto btn btn-default btn-primary mr-3" v-on:click="modalExtras = false">{{ __('Close') }}</button>
                    </div>
                </div>
            </modal>

            <template v-if="column.field == 'Relation'">
                <button class="ml-auto btn btn-default mr-3" :class="{ 'btn-danger': relationFail, ' btn-primary': !relationFail }" v-if="column.field == 'Relation'" v-on:click="checkField">{{ __('Set Relation') }}</button>
            </template>
        </div>
    </div>
</template>
<script>
import _ from 'lodash';
import Multiselect from 'vue-multiselect';
import api from '../api';
import Modal from './Modal';

export default {
    components: {
        modal: Modal,
        multiselect: Multiselect,
    },

    props: {
        id: {
            type: String,
            default: 'column',
            required: true,
        },
        column: {
            type: Object,
            default: function() {
                return { name: '' };
            },
            required: true,
        },
        v: {
            type: Object,
            required: true,
        },
        tables: {
            type: Array,
            required: true,
        },
        settings: {
            type: Object,
            required: true,
        },
        hasExtraColumn: {
            type: Boolean,
            default: false,
            required: true,
        },
        countries: {
            type: Array,
            default: function() {
                return [];
            },
            required: false,
        },
    },

    data: () => ({
        fields: {
            avatar: {
                name: 'Avatar',
            },
            boolean: {
                name: 'Boolean',
            },
            code: {
                name: 'Code',
                json: false,
                language: [
                    'dockerfile',
                    'javascript',
                    'markdown',
                    'nginx',
                    'php',
                    'ruby',
                    'sass',
                    'shell',
                    'vue',
                    'xml',
                    'yaml',
                ],
            },
            country: {
                name: 'Country',
            },
            currency: {
                name: 'Currency',
                format: '%i',
            },
            date: {
                name: 'Date',
                format: null,
            },
            dateTime: {
                name: 'DateTime',
                format: null,
            },
            file: {
                name: 'File',
            },
            image: {
                name: 'Image',
            },
            gravatar: {
                name: 'Gravatar',
            },
            id: {
                name: 'ID',
            },
            markdown: {
                name: 'Markdown',
            },
            number: {
                name: 'Number',
                min: null,
                max: null,
                step: null,
            },
            password: {
                name: 'Password',
            },
            place: {
                name: 'Place',
                cities: false,
                countries: [],
            },
            select: {
                name: 'Select',
                options: {},
                diplayLabels: false,
            },
            status: {
                name: 'Status',
            },
            text: {
                name: 'Text',
            },
            textarea: {
                name: 'Textarea',
            },
            timezone: {
                name: 'Timezone',
            },
            trix: {
                name: 'Trix',
                files: false,
                disk: null,
            },
            relation: {
                name: 'Relation',
            },
        },
        selectShow: {
            all: 'All',
            hideFromIndex: 'hideFromIndex',
            hideFromDetail: 'hideFromDetail',
            hideWhenCreating: 'hideWhenCreating',
            hideWhenUpdating: 'hideWhenUpdating',
            onlyOnIndex: 'onlyOnIndex',
            onlyOnDetail: 'onlyOnDetail',
            onlyOnForms: 'onlyOnForms',
            exceptOnForms: 'exceptOnForms',
            disabled: 'Disabled on views',
        },
        columnRelations: {
            hasOne: 'HasOne',
            hasMany: 'HasMany',
            belongsTo: 'BelongsTo',
            belongsToMany: 'BelongsToMany',
            // morphMany: 'MorphMany',
            // morphTo: 'MorphTo',
            // morphToMany: 'MorphToMany',
        },
        codeSelect: {
            json: 'json',
            dockerfile: 'dockerfile',
            javascript: 'javascript',
            markdown: 'markdown',
            nginx: 'nginx',
            php: 'php',
            ruby: 'ruby',
            sass: 'sass',
            shell: 'shell',
            vue: 'vue',
            xml: 'xml',
            yaml: 'yaml',
        },
        extraPopupWidth: 800,
        modalRelations: false,
        modalVisual: false,
        related_columns: {},
        modalExtras: false,
        select_key: null,
        select_option: null,
        referenceUrl: null,
        selectedCities: [],
        selectedLoading: null,
        selectedFailed: [],
    }),

    methods: {
        checkField() {
            //Relation
            if (this.column.field == 'Relation') {
                if (!this.column.hasOwnProperty('related_table')) {
                    this.$set(this.column, 'related_table', null);
                }
                if (!this.column.hasOwnProperty('related_column')) {
                    this.$set(this.column, 'related_column', 'id');
                }

                this.modalRelations = true;
            }

            //Select
            if (this.column.field == 'Select') {
                if (!this.column.hasOwnProperty('options')) {
                    this.$set(this.column, 'options', {});
                }
                this.extraPopupWidth = 800;
            }

            //code
            if (this.column.field == 'Code') {
                if (!this.column.hasOwnProperty('language')) {
                    this.$set(this.column, 'language', null);
                }
                this.extraPopupWidth = 400;
            }

            //Boolean
            if (this.column.field == 'Boolean') {
                if (!this.column.hasOwnProperty('trueValue')) {
                    this.$set(this.column, 'trueValue', null);
                }
                if (!this.column.hasOwnProperty('falseValue')) {
                    this.$set(this.column, 'trueValue', null);
                }
                this.extraPopupWidth = 400;
            }

            //Currency | Date | DateTime
            if (
                this.column.field == 'Currency' ||
                this.column.field == 'Date' ||
                this.column.field == 'DateTime'
            ) {
                if (!this.column.hasOwnProperty('format')) {
                    this.$set(this.column, 'format', null);
                }
                this.extraPopupWidth = 400;
            }

            //File | Image | Avatar
            if (
                this.column.field == 'File' ||
                this.column.field == 'Image' ||
                this.column.field == 'Avatar'
            ) {
                if (!this.column.hasOwnProperty('disk')) {
                    this.$set(this.column, 'disk', null);
                }
                if (!this.column.hasOwnProperty('prunable')) {
                    this.$set(this.column, 'prunable', true);
                }
                this.extraPopupWidth = 400;
            }

            //Trix
            if (this.column.field == 'Trix') {
                if (!this.column.hasOwnProperty('disk')) {
                    this.$set(this.column, 'disk', null);
                }
                this.extraPopupWidth = 400;
            }

            //Number
            if (this.column.field == 'Number') {
                if (!this.column.hasOwnProperty('min')) {
                    this.$set(this.column, 'min', null);
                }
                if (!this.column.hasOwnProperty('max')) {
                    this.$set(this.column, 'max', null);
                }
                if (!this.column.hasOwnProperty('step')) {
                    this.$set(this.column, 'step', null);
                }
                this.extraPopupWidth = 400;
            }

            //Place
            if (this.column.field == 'Place') {
                if (!this.column.hasOwnProperty('cities')) {
                    this.$set(this.column, 'cities', []);
                }
                if (!this.column.hasOwnProperty('onlyCities')) {
                    this.$set(this.column, 'onlyCities', false);
                }
                this.extraPopupWidth = 400;
            }

            //Status
            if (this.column.field == 'Status') {
                if (!this.column.hasOwnProperty('loadingWhen')) {
                    this.$set(this.column, 'loadingWhen', []);
                }
                if (!this.column.hasOwnProperty('failedWhen')) {
                    this.$set(this.column, 'failedWhen', []);
                }
                this.extraPopupWidth = 400;
            }
        },

        changeTableRelated() {
            api.getColumns(this.column.related_table).then(result => {
                this.related_columns = result.columns;
            });
        },

        setColumnLabel() {
            let label = this.column.name.split('_').join(' ');
            this.column.label = label.replace(/\b\w/g, l => l.toUpperCase());
        },

        cancelRelation() {
            this.modalRelations = false;
            this.column.related_table = null;
            this.column.related_column = null;
            this.setColumnField();
        },

        showExtrasModal() {
            this.checkModalSize();
            this.checkModalReference();
            this.modalExtras = true;
        },

        checkModalSize() {
            if (this.column.field == 'Relation') {
                this.extraPopupWidth = 800;
            } else {
                this.extraPopupWidth = 400;
            }
        },

        checkModalReference() {
            if (this.column.field == 'Select') {
                this.referenceUrl =
                    'https://nova.laravel.com/docs/1.0/resources/fields.html#select-field';
            } else if (this.column.field == 'Code') {
                this.referenceUrl =
                    'https://nova.laravel.com/docs/1.0/resources/fields.html#code-field';
            } else if (this.column.field == 'Boolean') {
                this.referenceUrl =
                    'https://nova.laravel.com/docs/1.0/resources/fields.html#customizing-true-false-values';
            } else if (this.column.field == 'Currency') {
                this.referenceUrl =
                    'https://nova.laravel.com/docs/1.0/resources/fields.html#currency-field';
            } else if (this.column.field == 'Date') {
                this.referenceUrl =
                    'https://nova.laravel.com/docs/1.0/resources/fields.html#date-field';
            } else if (this.column.field == 'DateTime') {
                this.referenceUrl =
                    'https://nova.laravel.com/docs/1.0/resources/fields.html#datetime-field';
            } else if (this.column.field == 'File') {
                this.referenceUrl =
                    'https://nova.laravel.com/docs/1.0/resources/file-fields.html#defining-the-field';
            } else if (this.column.field == 'Image') {
                this.referenceUrl =
                    'https://nova.laravel.com/docs/1.0/resources/file-fields.html#images';
            } else if (this.column.field == 'Avatar') {
                this.referenceUrl =
                    'https://nova.laravel.com/docs/1.0/resources/file-fields.html#avatars';
            } else if (this.column.field == 'Trix') {
                this.referenceUrl =
                    'https://nova.laravel.com/docs/1.0/resources/fields.html#file-uploads';
            } else if (this.column.field == 'Number') {
                this.referenceUrl =
                    'https://nova.laravel.com/docs/1.0/resources/fields.html#number-field';
            } else if (this.column.field == 'Place') {
                this.referenceUrl =
                    'https://nova.laravel.com/docs/1.0/resources/fields.html#searchable-countries';
            } else if (this.column.field == 'Status') {
                this.referenceUrl =
                    'https://nova.laravel.com/docs/1.0/resources/fields.html#status-field';
            } else {
                this.referenceUrl = null;
            }
        },

        addOption() {
            if (!this.column.hasOwnProperty(this.select_key)) {
                this.$set(this.column.options, this.select_key.toLowerCase(), this.select_option);
                this.select_key = null;
                this.select_option = null;
            }
        },

        removeOption(key) {
            this.$delete(this.column.options, key);
        },

        setColumnField() {
            let field;
            if (this.column.name == 'id') {
                field = 'ID';
            } else if (this.column.name.includes('_id')) {
                field = 'Relation';
            } else if (this.column.type == 'integer') {
                field = 'Number';
            } else if (this.column.type == 'boolean') {
                field = 'Boolean';
            } else if (this.column.type == 'date') {
                field = 'Date';
            } else if (this.column.type == 'datetime') {
                field = 'DateTime';
            } else if (this.column.name == 'image') {
                field = 'Image';
            } else {
                field = 'Text';
            }

            this.$set(this.column, 'field', field);
        },

        setColumnRequire() {
            if (this.settings.required.value == true) {
                if (this.column.nullable) {
                    this.$set(this.column, 'required', false);
                } else {
                    this.$set(this.column, 'required', true);
                }
            } else {
                this.$set(this.column, 'required', false);
            }
        },

        setColumnShowIn() {
            this.column.show = 'all';
            if (
                this.column.name == 'updated_at' ||
                this.column.name == 'created_at' ||
                this.column.name == 'deleted_at'
            ) {
                this.$set(this.column, 'show', 'disabled');
            }
        },

        setColumnSortable() {
            if (this.settings.sortable.value == true) {
                this.column.sortable = true;
            }
            if (
                this.column.name == 'updated_at' ||
                this.column.name == 'created_at' ||
                this.column.name == 'deleted_at'
            ) {
                this.$set(this.column, 'sortable', false);
            }
        },

        setColumnRelations() {
            if (this.column.name.includes('_id')) {
                this.$set(this.column, 'relation', null);
            }
        },

        addLoading(tag) {
            this.column.loadingWhen.push(tag);
        },

        addFailed(tag) {
            this.column.failedWhen.push(tag);
        },
    },

    computed: {
        shouldShow: function() {
            if (_.includes(this.settings.hidden.value, this.column.name)) {
                return false;
            }

            return true;
        },

        hasRelation: function() {
            if (this.column.field == 'Relation') {
                return true;
            }

            return false;
        },

        relationFail: function() {
            this.v.$touch();

            if (
                this.v.relation.$error ||
                this.v.related_table.$error ||
                this.v.related_column.$error
            ) {
                return true;
            }

            return false;
        },

        hasOptions: function() {
            if (this.column.hasOwnProperty('options')) {
                if (_.size(this.column.options) > 0) {
                    return true;
                }
            }

            return false;
        },

        columnHasExtra: function() {
            let hasExtra = false;
            switch (this.column.field) {
                case 'Select':
                    hasExtra = true;
                    break;
                case 'Code':
                    hasExtra = true;
                    break;
                case 'Boolean':
                    hasExtra = true;
                    break;
                case 'Currency':
                    hasExtra = true;
                    break;
                case 'Date':
                    hasExtra = true;
                    break;
                case 'DateTime':
                    hasExtra = true;
                    break;
                case 'File':
                    hasExtra = true;
                    break;
                case 'Image':
                    hasExtra = true;
                    break;
                case 'Avatar':
                    hasExtra = true;
                    break;
                case 'Trix':
                    hasExtra = true;
                    break;
                case 'Number':
                    hasExtra = true;
                    break;
                case 'Place':
                    hasExtra = true;
                    break;
                case 'Status':
                    hasExtra = true;
                    break;
                default:
                    hasExtra = false;
            }

            if (this.column.show == 'disabled') {
                hasExtra = false;
            }

            return hasExtra;
        },
    },

    watch: {
        'column.relation': function(relation) {
            if (relation == 'belongsTo') {
                if (!this.column.hasOwnProperty('searchable')) {
                    this.$set(this.column, 'searchable', true);
                }
            } else {
                this.$delete(this.column, 'searchable');
            }
        },
        selectedCities: function(newValues) {
            this.column.cities = newValues.map(obj => obj.code);
        },
    },

    created() {
        this.setColumnLabel();
        this.setColumnField();
        this.setColumnRelations();
        this.setColumnRequire();
        this.setColumnShowIn();
        this.setColumnSortable();
    },
};
</script>
<style scoped lang="scss">
.column {
    font-weight: 300;
    color: #252d37;
    border-top-width: 1px;
    border-bottom-width: 1px;
    border-color: #e3e7eb;
    padding-left: 0.75rem;
    padding-right: 0.75rem;
    height: 3.8125rem;
    line-height: 3.8125rem;
    flex: 1 1 0;
}

.refs {
    text-decoration: none;
    color: var(--primary);
}
</style>
