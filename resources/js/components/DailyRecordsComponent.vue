<template>
    <div class="page-wrapper">
        <div class="card">
            <div class="card-header">
                Devices History
            </div>
            <div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Item Info</th>
                        <th scope="col">Assigned User</th>
                        <th scope="col">In time</th>
                        <th scope="col">Out Time</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, key) in attendances">
                        <td scope="row">{{key+1 + '.' }}</td>
                        <td>{{item.item_name}}</td>
                        <td>{{item.email}}</td>
                        <td>{{item.in_time}}</td>
                        <td>{{item.deleted_at}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "DailyRecordsComponent",
        props:['attendances']
    }
</script>

<style scoped>

</style>
