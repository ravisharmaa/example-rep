<template>
    <div>
        <div class="row justify-content-center align-middle">
            <div class="subscription">
                <div class="card loginDiv">
                    <div class="card-header justify-content-center" >Create Attendance</div>
                    <div class="card-body">
                        <form action="" @submit.prevent="attend">
                            <div class="form-group row">
                                <label for="email">Email:</label>
                                <input id= "email" type="text" v-model="formData.email" class="form-control col-form-label"
                                       :class=" this.error ? 'is-invalid':''">
                                <div v-if ="error" class = "invalid-feedback">
                                    {{formData.email}} Please provide a valid email
                                </div>
                            </div>

                            <div class="form-group row">
                                    <toggle-button v-if="validEmail"
                                        id="changed-font"
                                        v-model="state"
                                        @change="getDevices"
                                        :width="100"
                                        :height="40"
                                        :speed="480"
                                        :value="false"
                                        :color="{checked: '#2756B7', unchecked: '#FF877B'}"
                                        :labels="{checked: 'In', unchecked: 'Out'}"/>
                            </div>

                            <div class="form-group row" v-if="validEmail">
                                <label for="device">Devices:</label>
                                <multiselect v-model="formData.item_name"
                                             :options="devices"
                                                :multiple="true"
                                             :clear-on-select="true"
                                            >

                                </multiselect>

                            </div>

                            <div class="form-group row">
                                <button class="btn btn-primary justify-">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import {userSubscriptions, deviceAttendances, attendancesUrl, subscriptionAttendances} from '../utilities/constants';
    import swal from 'sweetalert';
    import Multiselect from 'vue-multiselect';
    import {ToggleButton} from 'vue-js-toggle-button';

    export default {
        data() {
            return {
                formData: {
                    email: '',
                    item_name: []
                },
                devices: [],
                error: false,
                checkIn: true,
                userSubscriptions: userSubscriptions,
                deviceAttendances: deviceAttendances+ '/',
                attendancesUrl: attendancesUrl,
                subscriptionAttendances: subscriptionAttendances,
                state:false,
                validEmail: false
            }
        },

        components:{
            Multiselect: Multiselect,
            ToggleButton: ToggleButton
        },

        watch: {
            'formData.email': function (value) {
                let emailRegex = /\S+@\S+\.\S+/;
                if (emailRegex.test(value)) {
                    this.error = false;
                    this.validEmail = true;
                    this.getDevices(this.state)
                } else {
                    this.error = true;
                    this.devices = []
                }
            }
        },

        methods: {
            getDevices() {
                if (this.state) {
                    axios.get(`${this.userSubscriptions}${this.formData.email}/subscriptions?attended=1`).then(({data}) => {
                        if (data.subscriptions.length) {
                            this.devices = data.subscriptions.map(device => {
                                return device.item_name;
                            });
                        } else {
                            this.devices = [];
                        }
                    });
                } else {
                    let subscribedDevices = [];
                    let attendedDevices = [];
                    axios.get(`${this.userSubscriptions}${this.formData.email}/subscriptions`).then(({data}) => {
                        data.subscriptions.map(item => {
                            subscribedDevices.push(item.item_name);
                        });

                        axios.get(`${this.userSubscriptions}${this.formData.email}/subscriptions?attended=1`).then(({data}) => {
                            if (data.subscriptions.length) {
                               data.subscriptions.map(item => {
                                   attendedDevices.push(item.item_name);
                               });

                             this.devices = subscribedDevices.filter(value => {
                                 return attendedDevices.indexOf(value) === -1;
                              });

                           } else {
                               this.devices = subscribedDevices;
                           }
                        });
                    });
                }
            },

            attend() {
                let method = 'patch';

                if (!this.state) {
                    method = 'post';
                }

                axios[method](this.subscriptionAttendances, this.formData).then((response) => {

                    if (response.status === 200) {
                        swal({
                            title: response.data,
                            icon:"success"
                        });

                        this.formData.email = '';
                        this.formData.item_name = '';
                        this.validEmail = false;
                        this.state = false;
                        this.error = false;
                    }

                }).catch(({response}) => {
                    if (response.status === 403) {
                        swal({
                            title: response.data.message,
                            icon:"error"
                        });

                    }

                    if (response.status === 404) {
                        swal({
                            title: 'The related devices were not found in the store, please check again',
                            icon:"error"
                        });


                    }

                    this.formData.email = '';
                    this.formData.item_name = '';
                    this.validEmail = false;
                });


            }
        }
    }
</script>
