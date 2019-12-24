<template>
    <div>
        <div class="row justify-content-center align-middle">
            <div class="subscription">
                <div class="card loginDiv">
                    <div class="card-header">Create Attendance</div>
                    <div class="card-body">
                        <form action="" @submit.prevent="attend">
                            <div class="form-group row">
                                <label for="email">Email:</label>
                                <input id= "email" type="text" v-model="formData.email" class="form-control col-form-label"
                                       :class=" this.error ? 'is-invalid':'is-valid'">
                                <div v-if ="error" class = "invalid-feedback">
                                    {{formData.email}} {{error}}
                                </div>
                            </div>

                            <div class="form-group row">
                                <input type="radio" id="one" value="in" v-model="state" @change="getDevices">
                                <label for="one">In</label>
                                <br>
                                <input type="radio" id="two" value="out" v-model="state" @change="getDevices">
                                <label for="two">Out</label>
                            </div>

                            <div class="form-group row">
                                <label for="device">Devices:</label>
                                <multiselect v-model="formData.item_name"
                                             :options="devices"
                                                :multiple="true"
                                             :clear-on-select="true"
                                            >

                                </multiselect>

                            </div>

                            <div class="form-group row">
                                <button class="btn btn-primary">In</button>
                                <button class="btn btn-danger" @click="reverse">Out</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import {devicesUrl, attendancesUrl, deviceAttendances} from '../utilities/constants';
    import swal from 'sweetalert';
    import Multiselect from 'vue-multiselect';
    import _ from 'underscore';

    export default {
        data() {
            return {
                formData: {
                    email: '',
                    item_name: []
                },
                devices: [],
                error: 'Please provide a valid email',
                checkIn: true,
                attendancesUrl: attendancesUrl,
                deviceAttendances: deviceAttendances+ '/',
                state:''
            }
        },

        components:{
            Multiselect: Multiselect
        },

        watch: {
            'formData.email': function (value) {
                let emailRegex = /\S+@\S+\.\S+/;
                if (emailRegex.test(value)) {
                    this.error = '';
                    //this.getDevices(value)
                } else {
                    this.error = ' is not a valid email';
                    this.devices = []
                }
            }
        },

        methods: {
            getDevices() {

                //debugger;
                if (this.state === 'in') {
                    axios.get(`${this.deviceAttendances}${this.formData.email}`).then((response) => {
                        if (response.data.length) {
                            this.devices = response.data.map(data => {
                                return data.item_name;
                            });
                        }
                    });
                } else {
                    let devicesFromApi = [];
                    let localDevices = [];
                    axios.get(`${devicesUrl}${this.formData.email}`).then(({data}) => {
                        data.results.map(item => {
                            devicesFromApi.push(item.item_name);
                        });

                        axios.get(`${this.deviceAttendances}${this.formData.email}`).then((response) => {
                           if (response.data.length) {

                               response.data.map(item => {
                                   localDevices.push(item.item_name);
                               });

                             this.devices = devicesFromApi.filter(value => {
                                  return localDevices.indexOf(value === -1);
                              });
                              // this.devices = localDevices.filter(item_name => devicesFromApi.includes(item_name));
                               console.log(this.devices);
                               //debugger;
                           } else {
                               this.devices = devicesFromApi;
                           }
                        });
                    });

                }
                // let devicesFromApi = [];
                // axios.get(`${devicesUrl}${value}`).then(({data}) => {
                //     data.results.map(data => {
                //        devicesFromApi.push(data.item_name);
                //     });
                //
                //     axios.get(`${this.deviceAttendances}${value}`).then((response) => {
                //         if (response.data.length) {
                //             this.devices = _.union(devicesFromApi, response.data.item_name)
                //         } else {
                //             this.devices = data.results.map(data => {
                //                 return data.item_name
                //             });
                //         }
                //
                //
                //     });
                //
                //
                // }).catch(error => {
                //     this.error = 'could not find any device related to the user';
                // });

            },

            attend() {
                let method = 'post';

                if (!this.checkIn) {
                    method = 'patch';
                }

                axios[method](this.attendancesUrl, this.formData).then((response) => {
                    if (response.status === 200) {
                        swal({
                            title: response.data,
                            icon:"success"
                        });

                        this.formData.email = '';
                        this.formData.item_name = '';
                    }

                    window.location.reload();

                }).catch(({response}) => {
                    if (response.status === 403) {
                        swal({
                            title: response.data.message,
                            icon:"error"
                        });

                    }

                    if (response.status === 500) {
                        swal({
                            title: 'You need to register the device for taking before taking in',
                            icon:"error"
                        });


                    }

                    this.formData.email = '';
                    this.formData.item_name = '';
                });


            },

            reverse() {
                return this.checkIn = !this.checkIn;
            }
        }
    }
</script>
