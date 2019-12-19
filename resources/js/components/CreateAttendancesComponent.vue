<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card loginDiv">
                    <div class="card-header">Create Subscription</div>
                    <div class="card-body">
                        <form action="" @submit.prevent="addDevice">
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
                                <select name="device" id="device" v-model="formData.device" class="form-control col-form-label">
                                    <option value="" selected disabled>Choose</option>
                                    <option v-for="device in devices" v-text="device.item_name"></option>
                                </select>
                            </div>

                            <div class="form-group row">
                                <button class="btn btn-primary">In</button>
                                <button class="btn btn-danger">Out</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import {devicesUrl} from '../utilities/constants'

    export default {
        data () {
            return {
                formData:{
                    email:'',
                    device:''
                },
                devices: [],
                error:'Please provide a valid email'
            }
        },

        watch :{
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

        methods:{
            getDevices(value) {
                axios.get(`${devicesUrl}${value}`).then(({data}) =>  {
                    this.devices = data.results;
                }).catch(error => {
                    this.error = 'could not find any device related to the user';
                });
            },

            addDevice() {

            }
        }
    }
</script>
