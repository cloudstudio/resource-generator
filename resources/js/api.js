export default {
    getTables() {
        return window.axios
            .post('/nova-vendor/resource-generator/get-tables')
            .then(response => response.data);
    },

    getColumns(table) {
        return window.axios
            .post('/nova-vendor/resource-generator/get-columns', {
                table: table,
            })
            .then(response => response.data);
    },

    checkFiles(createModel, singular) {
        return window.axios
            .post('/nova-vendor/resource-generator/check-file', {
                createModel: createModel,
                singular: singular,
            })
            .then(response => response.data);
    },

    generateResources(table, singular, title, search, createModel, columns) {
        return window.axios
            .post('/nova-vendor/resource-generator/generate-resource', {
                table: table,
                singular: singular,
                title: title,
                search: search,
                createModel: createModel,
                columns: columns,
            })
            .then(response => response.data);
    },

    getSettings() {
        return window.axios
            .get('/nova-vendor/resource-generator/settings-get')
            .then(response => response.data);
    },

    saveSettings(settings) {
        return window.axios
            .post('/nova-vendor/resource-generator/settings-set', {
                settings: settings,
            })
            .then(response => response.data);
    },

    resetSettings() {
        return window.axios
            .post('/nova-vendor/resource-generator/settings-reset')
            .then(response => response.data);
    },
};
