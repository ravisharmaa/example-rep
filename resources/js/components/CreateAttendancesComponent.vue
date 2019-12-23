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
    import Multiselect from 'vue-multiselect'

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
                deviceAttendances: deviceAttendances+ '/'
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
                    this.getDevices(value)
                } else {
                    this.error = ' is not a valid email';
                    this.devices = []
                }
            }
        },

        methods: {
            getDevices(value) {

                let devicesFromApi = [];
                axios.get(`${devicesUrl}${value}`).then(({data}) => {
                    data.results.map(data => {
                       devicesFromApi.push(data.item_name);
                    });

                    axios.get(`${this.deviceAttendances}${value}`).then((response) => {
                        debugger;
                        if (response.data.length) {
                           response.data.map(item => {
                              let index = devicesFromApi.indexOf(item.item_name);
                              if (index > -1) {
                                  devicesFromApi.splice(index,1);
                                  this.devices = devicesFromApi;
                              }
                           });
                        } else {
                            this.devices = data.results.map(data => {
                                return data.item_name
                            });
                        }


                    });


                }).catch(error => {
                    this.error = 'could not find any device related to the user';
                });
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
