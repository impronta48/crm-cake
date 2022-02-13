var app = new Vue({
    el: '#app',
    data() {
        return {
            destinatari: [],
            campaign_id: $campaign_id,
            loading: false,
        }
    },
    async created() {
        this.fetchRows();
    },
    methods: {
        fetchRows() {
            let url = `/campaigns/status/${this.campaign_id}.json`;
            this.loading = true;
            let promise = axios.get(url);
            return promise.then(response => {
                    this.destinatari = response.data.destinatari;
                    this.loading = false;
                })
                .catch(error => {
                    console.log(error);
                    this.loading = false;
                });

        },

        niceDate: function(dt) {
            let d = DateTime.fromISO(dt);
            return d.toLocaleString();
        },

    },
    computed: {}
});