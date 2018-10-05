Nova.booting((Vue, router) => {
    router.addRoutes([
        {
            name: 'resource-generator.home',
            path: '/resource-generator/generate',
            component: require('./components/Tool'),
        },

        {
            name: 'resource-generator.settings',
            path: '/resource-generator/settings',
            component: require('./components/Settings'),
        },
    ]);
});
