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
                { key: 'Provincia', sortable: true },
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
            provincia: null,
            selected: [],
            persone: [],
            selectAllStatus: false,
            multiTags: '',
            showAddTag: false,
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

        if (urlParams.has('provincia')) {
            this.provincia = urlParams.get('provincia');
        }
    },
    methods: {
        isCurrentPage(page) {

            // return this.pagination.page === page;
            return this.pagination.currentPage === page;
        },
        changePage(page) {
            if (page > this.pagination.pageCount) {
                page = this.pagination.pageCount;
            }
            // this.pagination.page = page;
            this.pagination.currentPage = page;
            //Dopo la pagination esterna devo ricaricare
            this.$root.$emit('bv::refresh::table', 'contacts');
        },
        fetchRows() {
            // let url = '/persone.json?&page=' + this.pagination.page;
            let url = '/persone.json?&page=' + this.pagination.currentPage;
            if (this.q !== null) {
                url += '&q=' + this.q;
            }
            if (this.nazione !== null) {
                url += '&nazione=' + this.nazione;
            }
            if (this.provincia !== null) {
                url += '&provincia=' + this.provincia;
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
            this.selectAllStatus = false;
            let promise = axios.get(url);
            
            return promise.then(response => {
                    // this.persone = response.data.persone.results;
                    this.pagination = response.data.persone.params;

                    this.persone = response.data.persone;
                    // this.pagination = response.data.pagination.Persone;
                    this.pagination = response.data.pagination;
                    console.log("pagination: " + JSON.stringify(this.pagination));
                    return (this.persone);
                })
                .catch(error => {
                    console.log(error);
                    this.persone = [];
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
                    $res = axios.delete('/persone/delete/' + id).then(() => {
                        this.$refs.tab.refresh();
                    });
                }
            });
        },
        deleteMulti() {
            this.$bvModal.msgBoxConfirm("Vuoi davvero cancellare le persone selezionate? ", {}).then(value => {
                if (value) {
                    let ids = this.getSelectedRowsIds();
                    $res = axios.delete('/persone/delete', {
                        data: { ids: ids },
                    }).then(() => {
                        this.$refs.tab.refresh();
                    });
                }
            });

        },
        niceDate: function(dt) {
            let d = DateTime.fromISO(dt);
            return d.toLocaleString();
        },
        search() {
            this.$router.replace({ path: "persone", query: { q: this.q, tags: this.tags, nazione: this.nazione, provincia: this.provincia } });
            //const urlParams = new URLSearchParams(window.location.search);
            //this.q = urlParams.get('q');
            //this.tags = urlParams.getAll("tags");
            this.$root.$emit('bv::refresh::table', 'contacts');
        },
        onRowSelected(items) {
            this.selected = items
        },
        selectAllRows() {
            if (this.selectAllStatus) {
                this.$refs.tab.selectAllRows();
            } else {
                this.$refs.tab.clearSelected();
            }
        },
        getSelectedRowsIds() {
            return this.selected.map(x => x.id);
        },
        addTag() {
            this.showAddTag = true;
            this.multiTags = prompt("Inserisci i tag da aggiungere");
            console.log(this.multiTags);
            if (this.multiTags) {
                let ids = this.getSelectedRowsIds();
                $res = axios.post('/persone/add-tags', {
                    ids: ids,
                    tags: this.multiTags
                }).then(() => {
                    this.$refs.tab.refresh();
                });
            }
            this.showAddTag = false;
        }
    },
    computed: {
        pages: function() {
            let pages = [];
            // let from = this.pagination.page - Math.floor(this.pagination.perPage / 2);
            let from = this.pagination.currentPage - Math.floor(this.pagination.perPage / 2);

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
            if (this.provincia !== null) {
                url += '&provincia=' + this.provincia;
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