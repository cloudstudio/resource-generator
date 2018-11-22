<template>
    <div>
        <h1 class="mb-3 text-90 font-normal text-2xl">
            {{ __('Generator - Settings') }}
        </h1>
        <div class="card">
            <div class="flex flex-wrap border-b border-40">

                <template v-for="setting in settings">
                    <div class="w-full flex flex-wrap" :key="setting.name">
                        <div class="w-1/3 px-8 py-6">
                            <label class="block text-80 pt-2 leading-tight" for="table">
                                {{ setting.name }}
                            </label>
                            <small>{{ setting.help }}</small>
                        </div>
                        <div class="w-1/2 px-8 py-6">
                            
                            <template v-if="setting.type == 'String'">
                                <input class="w-full form-control form-input" id="resource_name" v-model="setting.value" v-on:input="fixBars($event, setting)">
                            </template>

                            <template v-if="setting.type == 'Boolean'">
                                <toggle-button v-model="setting.value" :sync="true" />
                            </template>

                            <template v-if="setting.type == 'Multiple'">
                                <multiselect v-model="setting.value" :taggable="true" :options="[]" :multiple="true" @tag="addOption($event, setting)"></multiselect>
                            </template>
                            
                        </div>
                    </div>
                </template>
            	
            </div>
            <div class="bg-30 flex px-8 py-4">

                <div class="ml-auto">
                    <button class="btn text-80 font-normal h-9 px-3 mr-3 btn-link" type="button" @click="resetCheck" v-if="!reset_button">
                        {{ __('Reset to defaults') }}
                    </button>

                    <button class="btn text-80 font-normal h-9 px-3 mr-3 btn-link" type="button" @click="reset"  v-if="reset_button">
                        {{ __('Are you sure?') }}
                    </button>

                    <button class="ml-auto btn btn-default btn-primary mr-3" dusk="create-and-add-another-button" type="button"  @click="saveSettings">
                        {{ __('Save options') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import _ from 'lodash';
import api from '../api';
import Multiselect from 'vue-multiselect';
import ToggleButton from 'vue-js-toggle-button/src/Button';

export default {
    components: {
        'toggle-button': ToggleButton,
        multiselect: Multiselect,
    },

    data: () => ({
        settings: {},
        reset_button: false,
    }),

    methods: {
        getSettings() {
            api.getSettings().then(result => {
                this.settings = result;
            });
        },

        saveSettings() {
            api.saveSettings(this.settings)
                .then(() => {
                    this.$toasted.show(this.__('Settings saved'), { type: 'success' });
                })
                .catch(() => {
                    this.$toasted.show(
                        this.__(
                            'Error saving settins. Please check your internet conecttion or remove your settings from storage directory'
                        ),
                        { type: 'error' }
                    );
                });
        },

        addOption(newValue, setting) {
            if (setting.value === null) {
                setting.value = [];
            }
            setting.value.push(newValue);
        },

        resetCheck() {
            this.reset_button = true;

            setTimeout(() => {
                this.reset_button = false;
            }, 3000);
        },

        reset() {
            api.resetSettings().then(() => {
                this.$toasted.show(this.__('Settings reset to default'), { type: 'success' });
                this.getSettings();
            });
        },

        fixBars: _.debounce(function(e, setting) {
            setting.value = setting.value.replace(/\//g, '\\');
            // setting.value +=  '\\';
            setting.value = setting.value.replace(/\\\\/g, '\\');
        }, 500),
    },

    mounted() {
        this.getSettings();
    },
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css">
</style>
