<script>
import { ref } from 'vue';


export default {
    setup(_, { emit }) {
        let gps_location = ref("");
        let response_text = ref("")
        let response_error = ref("")
        async function timeRegistration() {
            response_error.value = ""
            response_text.value = ""
            const requestOptions = {
                method: "POST",
                headers: { 'Content-Type': 'application/json', 'auth': localStorage.getItem('token') },
                body: JSON.stringify({
                    user_id: localStorage.getItem('user_id'),
                    gps_coordinates: gps_location.value
                })
            };

            try {
                const response = await fetch('http://localhost:8000/time_registration', requestOptions);
                response_text.value = response.statusText;
                emit('GetTableData');
            } catch (error) {
                response_error.value = "Something Went Wrong"
            }
        }
        return {
            gps_location,
            timeRegistration,
            response_text,
        };
    }
};
</script>

<template>
    <h2>Time Registration</h2>
    <p class="pico-color-green-400">{{ response_text }}</p>
    <p class="pico-color-pink-400">{{ response_error }}</p>
    <input v-model="gps_location" type="text" placeholder="gps location" />
    <button @click="timeRegistration">Check In/Check Out</button>
</template>

<style></style>
