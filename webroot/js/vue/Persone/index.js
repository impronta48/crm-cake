var DateTime = luxon.DateTime;

const router = new VueRouter({
    mode: 'history',
    routes: []
});

Vue.use(router);
Vue.component('v-select', VueSelect.VueSelect);

var app = new Vue({
    router,
    el: '#app',
    data() {
        return {
            colonne: [
                { key: 'id', sortable: false },
                { key: 'DisplayName', sortable: true },
                { key: 'Citta', sortable: true },
                { key: 'Nazione', sortable: true },
                { key: 'modified', sortable: true },
                { key: 'azioni' },
            ],
            pagination: {
                'page': 1,
            },
            q: '',
            tags: [],
            tagList: [],
            sortBy: "modified",
            sortDesc: true,
            isBusy: false,
            nazione: null,
        }
    },
    async created() {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);

        //Devo leggere l'url corrente
        if (urlParams.has('q')) {
            this.q = urlParams.get('q');
        }

        if (urlParams.has('tags[]')) {
            this.tags = urlParams.getAll('tags[]');
        }

        if (urlParams.has('tags')) {
            this.tags = urlParams.getAll('tags');
        }

        if (urlParams.has('nazione')) {
            this.tags = urlParams.get('nazione');
        }
    },
    methods: {
        isCurrentPage(page) {

            return this.pagination.page === page;
        },
        changePage(page) {
            if (page > this.pagination.pageCount) {
                page = this.pagination.pageCount;
            }
            this.pagination.page = page;
            //Dopo la pagination esterna devo ricaricare
            this.$root.$emit('bv::refresh::table', 'contacts');
        },
        fetchRows() {
            let url = '/persone.json?&page=' + this.pagination.page;
            if (this.q !== null) {
                url += '&q=' + this.q;
            }
            if (this.nazione !== null) {
                url += '&nazione=' + this.nazione;
            }
            if (this.tags !== null) {
                url += '&tags[]=' + this.tags;
            }
            if (this.sortBy !== null) {
                url += '&sort=' + this.sortBy;
            }
            if (this.sortDesc !== null) {
                let s = 'asc';
                if (this.sortDesc) {
                    s = 'desc';
                }
                url += '&direction=' + s;
            }

            let promise = axios.get(url);
            return promise.then(response => {
                    const persone = response.data.persone;
                    this.pagination = response.data.pagination.Persone;
                    return (persone);
                })
                .catch(error => {
                    console.log(error);
                    return [];
                });
        },
        fetchTags(search, loading) {
            if (search == undefined) {
                return [];
            }
            loading(true);

            let url = '/tags.json?&search=' + search;
            axios.get(url)
                .then(response => {
                    this.tagList = response.data.tags;
                    loading(false);
                    return;
                })
                .catch(error => {
                    console.log(error);
                    loading(false);
                    this.tagList = [];
                    return;
                });
        },
        async delPersone(id) {
            this.$bvModal.msgBoxConfirm("Vuoi davvero cancellare questa persona? " + id, {}).then(value => {
                if (value) {
                    $res = axios.delete('/persone/delete/' + id);
                    this.fetchRows();
                }
            });
        },
        niceDate: function(dt) {
            let d = DateTime.fromISO(dt);
            return d.toLocaleString();
        },
        search() {
            this.$router.replace({ path: "persone", query: { q: this.q, tags: this.tags, nazione: this.nazione } });
            //const urlParams = new URLSearchParams(window.location.search);
            //this.q = urlParams.get('q');
            //this.tags = urlParams.getAll("tags");
            this.$root.$emit('bv::refresh::table', 'contacts');
        },
        selectAll() {
            alert("seleziono tutti - ma non sono ancora pronto");
        }
    },
    computed: {
        pages: function() {
            let pages = [];
            let from = this.pagination.page - Math.floor(this.pagination.perPage / 2);

            if (from < 1) {
                from = 1;
            }
            let to = from + this.pagination.perPage - 1;
            if (to > this.pagination.pageCount) {
                to = this.pagination.pageCount;
            }

            while (from <= to) {
                pages.push(from);
                from++;
            }

            return pages;
        },
        qString: function() {
            let url = '?';
            if (this.q !== null) {
                url += '&q=' + this.q;
            }
            if (this.nazione !== null) {
                url += '&nazione=' + this.nazione;
            }
            if (this.tags !== null) {
                url += '&tags[]=' + this.tags;
            }
            return url;
        },
        tagArray: {
            get() {
                return this.tags;
            },
            set(newValue) {
                last = newValue[newValue.length - 1]
                if (typeof last == 'object') {
                    this.tags.push(last.id);
                } else {
                    this.tags = newValue;
                }
            }
        }


    }
});