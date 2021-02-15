var app = new Vue({
    el: '#app',
    data() {
        return {
            destinatari: [],
            campaign_id: $campaign_id,
        }
    },
    async created() {
        this.fetchRows();
    },
    methods: {
        fetchRows() {
            let url = `/campaigns/status/${this.campaign_id}.json`;

            let promise = axios.get(url);
            return promise.then(response => {
                    this.destinatari = response.data.destinatari;
                })
                .catch(error => {
                    console.log(error);
                });
        },

        niceDate: function(dt) {
            let d = DateTime.fromISO(dt);
            return d.toLocaleString();
        },
    },
    computed: {}
});