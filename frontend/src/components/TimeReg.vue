<script>
import { ref } from 'vue';

export default {
  setup() {
    let gps_location = ref("");
    let response_text = ref("")

    async function timeRegistration() {
      
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
        console.log(response);
        response_text.value = response.statusText;
      } catch (error) {
        console.error(error);
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
  <p class="pico-color-green-400">{{response_text}}</p>
  <input v-model="gps_location" type="text" placeholder="gps location"/>
  <button @click="timeRegistration">Check In/Check Out</button>
</template>

<style></style>
