<script setup>
import { useRouter } from 'vue-router';
const router = useRouter();

async function LogOut() {
    const requestOptions = {
        method: "POST",
        header: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            user_id: localStorage.getItem('user_id')
        })
    }
    const response = await fetch('http://localhost:8000/logout', requestOptions)
    console.log(response);

    localStorage.setItem('token', "");
    localStorage.setItem('user_id', "");
    router.push("/login")
}
</script>
<template>
    <header class="container">
        <nav>
            <div></div>
            <router-link to="/">Home</router-link>
            <router-link to="/user">User</router-link>
            <router-link to="/edit">Edit</router-link>
            <p @click="LogOut" style="cursor:pointer;text-decoration:underline">Log Out</p>
            <div></div>
        </nav>
    </header>
</template>