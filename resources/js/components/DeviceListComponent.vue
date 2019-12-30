<template>
    <div class="page-wrapper deviceList">

        <div class="container-fluid">

            <div class="card">
                <div class="card-header">
                    <div class="userName">
                        Devices Assigned to : {{name}}
                    </div>
                    <div class="deviceSearch">
                        <input type="text" class="form-control" v-model="curate" placeholder="Search">
                    </div>
                </div>
                <div>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Item Info</th>
                            <th scope="col">Item Serial</th>
                            <th scope="col">Assigned Date</th>
                            <th scope="col">Assigned By</th>
                            <th scope="col">Device Status</th>
                            <th scope="col">Request Access</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(result, key) in curated">
                            <td scope="row">{{key+1 + '.' }}</td>
                            <td>{{result.item_name}}</td>
                            <td>{{result.item_serial}}</td>
                            <td>{{result.assigned_date | humanize}}</td>
                            <td>{{result.assignee}}</td>
                            <td>{{result.status | format }}</td>
                            <td>
                                <a class="btn btnSecondary"
                                   :class="result.isSubscribed ? 'btn-success disabled' : 'btn-primary'"
                                   @click.prevent="subscribe(result)"
                                   v-text="result.isSubscribed ? 'Subscribed' : 'Request'"></a>

                                <a v-if="result.isSubscribed" class="btn btnSecondary"
                                   @click.prevent="revoke(result)">Return</a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</template>

<script>

    import {user} from "../utilities/auth";
    import moment from 'moment'
    import {devicesUrl, subscriptionsUrl, revokingUrl} from "../utilities/constants";
    import swal from 'sweetalert'

    export default {
        props: ['subscriptions'],

        data() {
            return {
                results: [],
                curate: '',
                name: user.name(),
                revokingUrl: revokingUrl
            }
        },

        filters: {
            format: function (value) {
                return value === "1" ? 'Assigned' : 'Returned';
            },

            humanize: function (value) {
                return moment(value).fromNow();
            }

        },

        async mounted() {
            this.getData();
        },

        computed: {
            curated() {
                return this.results.filter((result) => {
                    return result.item_name.toLowerCase().indexOf(this.curate.toLowerCase()) >= 0;
                });
            }
        },

        methods: {
            async subscribe(result) {
                swal({
                    title: 'Your request has been submitted for review.',
                    icon:"success"
                });
                result.isSubscribed = true;
                await axios.post(`${subscriptionsUrl}`, {
                    'item_id': result.item_id,
                    'item_name': result.item_name
                }).then((response) => {
                    if (response.status === 500) {
                        swal({
                            title: response.data,
                            icon:"error"
                        });
                    }
                }).catch(error => {
                    console.log(error)
                });

            },

            async revoke(result) {

                let deviceIntendedToReturn = this.subscriptions.find(subscription => {
                       return parseInt(result.item_id) === parseInt(subscription.item_id);
                });

                if (deviceIntendedToReturn === undefined) {
                    swal({
                        title: "You need to reload the page to return the device after asking for subscription",
                        icon:'error'
                    });
                } else {
                    axios.post(`${this.revokingUrl}${deviceIntendedToReturn.subscription_code}/delete`)
                        .then(response => {
                            if (response.status === 200) {
                                swal({
                                    title: response.data,
                                    icon:'success'
                                });
                            }
                        }).catch(error => {
                        console.log(error)
                    });
                    result.isSubscribed = false;
                }




            },

            getData() {
                let subscribedDevices = [];
                this.subscriptions.map((subscription) => {
                    subscribedDevices.push(
                        parseInt(subscription.item_id)
                    );
                });
                 axios.get(`${devicesUrl}${user.email()}`).then(({data}) => {
                    data.results.map((device) => {
                        device['isSubscribed'] = subscribedDevices.indexOf(parseInt(device.item_id)) !== -1;
                    });

                    this.results = data.results;


                }).catch(error => {
                    console.log('Error')
                });
            }
        }
    }
</script>
