<script setup lang="ts">
import TimeReg from '../components/TimeReg.vue'
import { onMounted, ref } from 'vue';
import {useRouter} from 'vue-router';

let tableData = ref([]);

async function GetTableData() {
    console.log(localStorage.getItem('user_id'));
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
    console.log(data);

    tableData.value = data;

}

let checkIn = ref("");
let checkOut = ref("");
let id = ref("");
let gps = ref("");
let error = ref("");
let password = ref("");
let password_error = ref("")
let password_success = ref("")
const router = useRouter();

function handleSelectionChange(e) {
    id.value = e.target.value;
    let object = tableData.value.find(x => x.id == e.target.value);
    checkIn.value = object.check_in_time;
    checkOut.value = object.check_out_time;
    gps.value = object.gps_coordinates;
}
async function Edit() {
    const requestOptions = {
        method: "PUT",
        headers: { 'Content-Type': 'application/json', 'auth': localStorage.getItem('token') },
        body: JSON.stringify({
            user_id: localStorage.getItem('user_id'),
            id: id.value,
            check_out_time: Date.parse(checkOut.value) / 1000,
            check_in_time: Date.parse(checkIn.value) / 1000,
            gps_coordinates: gps.value
        })

    }

    try {
        const response = await fetch(`http://localhost:8000/time_registration/${id.value}`, requestOptions);
        const responseData = await response.json();

        if (responseData.error) {
            error.value = responseData.error;

        } else {
            GetTableData()
        }

    } catch (error) {
    }
}
async function newPassword() {
    password_error.value = ""
    password_success.value = ""
    if (password.value == "") {
        password_error.value = "empty input"
    } else {

        const requestOptions = {
            method: "PUT",
            headers: { 'Content-Type': 'application/json', 'auth': localStorage.getItem('token') },
            body: JSON.stringify({
                user_id: localStorage.getItem('user_id'),
                password: password.value
            })

        }
        console.log(requestOptions);

        try {
            const response = await fetch(`http://localhost:8000/users/${localStorage.getItem('user_id')}`, requestOptions);
            const responseData = await response.json();

            if (responseData.error) {
                password_error.value = responseData.error;

            }
            if (response.status == 200) {
                password_success = "new password"
            }

        } catch (error) {
        }
    }
}
onMounted(() => {
    GetTableData();
});
async function LogOut() {
    const requestOptions = {
        method: "POST",
        header: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            user_id: localStorage.getItem('user_id')
        })
    }
    const response  = await fetch('http://localhost:8000/logout', requestOptions)
    console.log(response);
    
    localStorage.setItem('token', "");
    localStorage.setItem('user_id', "");
    router.push("/login")
}
</script>

<template>
    <main class="container">
        <div class="grid">
            <h1 class="">HomeView</h1>
            <div class="grid">
                <div></div>
                <div></div>
                <p @click="LogOut" style="cursor:pointer;text-decoration:underline">Log Out</p>
            </div>
        </div>
        <p class="pico-color-green-400">{{ password_success }}</p>
        <p class="pico-color-pink-400">{{ password_error }}</p>
        <input v-model="password" type="password" name="password" placlass="container" ceholder="Password"
            aria-label="Password" />
        <button @click="newPassword">Change Password</button>
        <TimeReg />
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
        <h3>Edit your time registrations</h3>
        <p class="pico-color-pink-400">{{ error }}</p>
        <select name="edit" aria-label="Select id" required @change="handleSelectionChange($event)">
            <option selected disabled value="">Select id</option>
            <option v-for="data in tableData" :key="data.id" :value="data.id" :data-check-in="data.check_in"
                :data-check-out="data.check_out">
                {{ data.id }}
            </option>
        </select>
        <label for="">Check In Time</label>
        <input v-model="checkIn" type="datetime-local" name="check_in" aria-label="Datetime local" />

        <label for="">Check Out Time</label>
        <input v-model="checkOut" type="datetime-local" name="check_out" aria-label="Datetime local" />
        <input v-model="gps" type="text" name="gps" placeholder="Text" aria-label="Text" />
        <button @click="Edit">Edit</button>
    </main>
</template>
<style></style>