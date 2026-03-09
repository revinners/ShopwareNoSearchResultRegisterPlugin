import './page/revinners-no-search-result-list';
import enGB from './snippet/en-GB.json';
import plPL from './snippet/pl-PL.json';

const { Module } = Shopware;

Module.register('revinners-no-search-result', {
    type: 'plugin',
    name: 'RevinnersNoSearchResult',
    title: 'revinners-no-search-result.general.mainMenuItemGeneral',
    description: 'revinners-no-search-result.general.description',
    color: '#ff6900',
    icon: 'regular-search',

    snippets: {
        'en-GB': enGB,
        'pl-PL': plPL,
    },

    routes: {
        list: {
            component: 'revinners-no-search-result-list',
            path: 'list',
        },
    },

    navigation: [{
        label: 'revinners-no-search-result.general.mainMenuItemGeneral',
        color: '#ff6900',
        path: 'revinners.no.search.result.list',
        icon: 'regular-search',
        parent: 'sw-catalogue',
        position: 100,
    }],
});



