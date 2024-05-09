<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router';

let text = ref(['Login', "Register"])
let error = ref("");
let personnumber = ref("");
let password = ref("");

let is_register = false;
let token = ref("");
const router = useRouter();

function ChangeToRegister() {
    is_register = !is_register
    text.value.reverse()
}

async function Authenticate() {

    if (is_register) {
        //register
        // console.log("register");
        
        const requestOptions = {
            method: "Post",
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ personnumber: personnumber.value, password: password.value })
        };
        try {

            const response = await fetch('http://localhost:8000/register', requestOptions);
            const data = await response.json();
            // console.log(data.message);
            
            if (data.message === "Account already registered") {
                error.value = "Account already registered"
            }
            else {
                error.value = "Account created"
            }
        } catch (error) {

        }
    } else {
        //login
        const requestOptions = {
            method: "Post",
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ personnumber: personnumber.value, password: password.value })
        };

        try {

            const response = await fetch('http://localhost:8000/login', requestOptions);
            const data = await response.json();
            console.log(data.message);
            if (data.message === "Invalid credentials") {
                error.value = data.message;
            } else {
                console.log(data);
                
                token.value = data[1]
                localStorage.setItem('token', token.value);
                localStorage.setItem('user_id', data[3]);
                router.push("/");
            }
        } catch (error) {

        }
    }
}
</script>

<template>
    <article class="">
        <h2>{{ text[0] }}</h2>
        <p class="pico-color-pink-400">{{ error }}</p>
        <input v-model="personnumber" type="text" name="text" placeholder="Person Number" aria-label="Number" />
        <input v-model="password" type="password" name="password" placlass="container" ceholder="Password"
            aria-label="Password" />
        <button @click="Authenticate">{{ text[0] }}</button>
        <p class="change" @click="ChangeToRegister">{{ text[1] }}-></p>
    </article>
</template>

<style scoped>
.change {
    cursor: pointer;
}
</style>
