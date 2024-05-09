<script setup>
import HeaderComp from '../components/HeaderComp.vue';
import { ref } from 'vue';
let password = ref("");
let password_error = ref("")
let password_success = ref("")
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
</script>
<template>
    <HeaderComp />
    <main class="container">
        <p class="pico-color-green-400">{{ password_success }}</p>
        <p class="pico-color-pink-400">{{ password_error }}</p>
        <label for="">Change Password</label>
        <input v-model="password" type="password" name="password" placlass="container" ceholder="Password"
            aria-label="Password" />
        <button @click="newPassword">Change Password</button>
    </main>
</template>
<style></style>