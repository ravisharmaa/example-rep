<template>
        <div class="page-wrapper">
            <div class="card">
                <div class="card-header">
                    Devices Assigned to : {{name}}
                    <div class="float-right">
                        <input type="text" v-model="curate" placeholder="Search">
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
                            <td><a class="btn btn-primary btn-sm" href="" @click.prevent="subscribe(result)">Request</a></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</template>

<script>

    import {user} from "../utilities/auth";
    import moment from 'moment'
    import {devicesUrl, subscriptionsUrl, subscriptionsRemoteUrl} from "../utilities/constants";

    export default {
        data() {
            return {
                results: [],
                curate:'',
                name: user.name()
            }
        },

        filters:{
            format: function(value) {
                return value === "1" ? 'Assigned':'Returned';
            },

            humanize: function (value) {
                return moment(value).fromNow();
            }

        },

        async mounted() {
            await axios.get(`${devicesUrl}${user.email()}`).then(({data}) => {
                this.results = data.results
            }).catch(error => {
                console.log('Error')
            });
        },

        computed: {
            curated () {
                return this.results.filter((result) => {
                    return result.item_name.toLowerCase().indexOf(this.curate.toLowerCase()) >= 0;
                });
            }
        },

        methods:{
             async subscribe(result) {
                 await axios.post(`${subscriptionsUrl}`, {'item_id':result.item_id,'item_name': result.item_name}).then(({data}) => {
                   /*if (data.status) {
                       let device = this.results.filter(result => {
                           return result.item_id === result.item_id;
                       });
                       this.results.splice(device,1);
                       axios.post(`${subscriptionsUrl}`, {'item':result.item_name,'user':user.email()}).then(response => {
                           console.log(response)
                       })
                   }*/
                    console.log(data)

               }).catch(error => {
                   console.log(error)
               })
            }
        }
    }
</script>
