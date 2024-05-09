<script setup lang="ts">
import TimeReg from '../components/TimeReg.vue';
import HeaderComp from '../components/HeaderComp.vue';
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';

let tableData = ref([]);

async function GetTableData() {
    const requestOptions = {
        method: "Post",
        headers: { 'Content-Type': 'application/json', 'auth': localStorage.getItem('token') },
        body: JSON.stringify({ user_id: localStorage.getItem('user_id') })
    };
    const response = await fetch('http://localhost:8000/time_registration/table', requestOptions);
    if (response.message == "Unautorized") {
        localStorage.setItem('token', "");
        localStorage.setItem('user_id', "");
    }

    const data = await response.json()

    tableData.value = data;

}
const router = useRouter();

onMounted(() => {
    GetTableData();
});

</script>

<template>
    <HeaderComp />
    <main class="container">
        <TimeReg @GetTableData="GetTableData" />
        <h2>Your Time Registrations</h2>
        <div style="overflow-x:auto;">
            <table>
                <tr>
                    <th>Id</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>GPS Coordinates</th>
                    <!-- <th>Edit</th> -->
                </tr>
                <tr v-for="item in tableData">
                    <td>{{ item.id }}</td>
                    <td>{{ item.check_in_time }}</td>
                    <td>{{ item.check_out_time }}</td>
                    <td>
                        <a v-if="item.gps_coordinates"
                            :href="`https://www.openstreetmap.org/?mlat=${item.gps_coordinates.split(',')[0]}&mlon=${item.gps_coordinates.split(',')[1]}`">
                            {{ item.gps_coordinates }}
                        </a>
                    </td>
                </tr>
            </table>
        </div>
        
    </main>
</template>
<style></style>