<template>
    <div class="page-wrapper deviceList">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="userName">
                        Devices History
                    </div>
                </div>
                <div>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Item Info</th>
                            <th scope="col">Assigned User</th>
                            <th scope="col">Out time</th>
                            <th scope="col">In Time</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(item, key) in attendances">
                            <td scope="row">{{key+1 + '.' }}</td>
                            <td>{{item.item_name}}</td>
                            <td>{{item.email}}</td>
                            <td>{{item.out_time}}</td>
                            <td>{{item.deleted_at}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import {dailyRecords} from '../utilities/constants'
    export default {
        name: "DailyRecordsComponent",
        data() {
            return {
                attendances:[]
            }
        },

        mounted() {
            axios.get(dailyRecords).then(({data}) => {
               this.attendances = data.dailyRecords;
            });
        }
    }
</script>

<style scoped>

</style>
