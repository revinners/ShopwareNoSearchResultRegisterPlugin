import template from './revinners-no-search-result-list.html.twig';

const { Component, Mixin } = Shopware;
const { Criteria } = Shopware.Data;

Component.register('revinners-no-search-result-list', {
    template,

    inject: ['repositoryFactory'],

    mixins: [
        Mixin.getByName('listing'),
    ],

    data() {
        return {
            items: null,
            isLoading: true,
            sortBy: 'count',
            sortDirection: 'DESC',
            total: 0,
        };
    },

    computed: {
        repository() {
            return this.repositoryFactory.create('revinners_no_search_result');
        },

        columns() {
            return [
                {
                    property: 'phrase',
                    dataIndex: 'phrase',
                    label: this.$tc('revinners-no-search-result.list.columnPhrase'),
                    allowResize: true,
                    primary: true,
                },
                {
                    property: 'count',
                    dataIndex: 'count',
                    label: this.$tc('revinners-no-search-result.list.columnCount'),
                    allowResize: true,
                },
                {
                    property: 'firstSearchedAt',
                    dataIndex: 'firstSearchedAt',
                    label: this.$tc('revinners-no-search-result.list.columnFirstSearchedAt'),
                    allowResize: true,
                },
                {
                    property: 'lastSearchedAt',
                    dataIndex: 'lastSearchedAt',
                    label: this.$tc('revinners-no-search-result.list.columnLastSearchedAt'),
                    allowResize: true,
                },
            ];
        },
    },

    methods: {
        formatDate(value) {
            return Shopware.Utils.format.date(value, {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
            });
        },

        async getList() {
            this.isLoading = true;

            const criteria = new Criteria(this.page, this.limit);
            criteria.addSorting(Criteria.sort(this.sortBy, this.sortDirection));

            const result = await this.repository.search(criteria, Shopware.Context.api);

            this.items = result;
            this.total = result.total;
            this.isLoading = false;
        },
    },
});

