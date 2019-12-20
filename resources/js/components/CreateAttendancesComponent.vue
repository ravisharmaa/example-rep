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
                                <select name="device" id="device"
                                        :class="this.error?'is-invalid':'is-valid'"
                                        v-model="formData.device"
                                        class="form-control col-form-label">
                                    <option value="" selected disabled>Choose</option>
                                    <option v-for="device in devices" v-text="device.item_name"></option>
                                </select>
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
    import {devicesUrl, attendancesUrl} from '../utilities/constants'
    import swal from 'sweetalert'

    export default {
        data() {
            return {
                formData: {
                    email: '',
                    item_name: ''
                },
                devices: [],
                error: 'Please provide a valid email',
                checkIn: true,
                attendancesUrl: attendancesUrl
            }
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
                axios.get(`${devicesUrl}${value}`).then(({data}) => {
                    this.devices = data.results;
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

                }).catch(({response}) => {
                    if (response.status === 403) {
                        swal({
                            title: response.data.message,
                            icon:"error"
                        });

                        this.formData.email = '';
                    }
                });
            },

            reverse() {
                return this.checkIn = !this.checkIn;
            }
        }
    }
</script>
