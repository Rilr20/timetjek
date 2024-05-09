<script setup>
import HeaderComp from '../components/HeaderComp.vue';
import { onMounted, ref } from 'vue';
let error = ref("");
let edit_success = ref("");
let tableData = ref([]);
let checkIn = ref("");
let checkOut = ref("");
let id = ref("");
let gps = ref("");

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
function handleSelectionChange(e) {
    error.value = ""
    edit_success.value = ""
    id.value = e.target.value;
    let object = tableData.value.find(x => x.id == e.target.value);
    checkIn.value = object.check_in_time;
    checkOut.value = object.check_out_time;
    gps.value = object.gps_coordinates;
}
async function Edit() {
    error.value = ""
    edit_success.value = ""
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
            edit_success.value = "Updated";
            GetTableData()
        }

    } catch (error) {
    }
}
onMounted(() => {
    GetTableData();
});
</script>
<template>
    <HeaderComp />
    <main class="container">
        <h3>Edit your time registrations</h3>
        <p class="pico-color-pink-400">{{ error }}</p>
        <p class="pico-color-green-400">{{ edit_success }}</p>
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