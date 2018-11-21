<template>
    <div id="app">
        <img alt="Vue logo" src="./assets/logo.png" class="vue-logo">
        <CustomEditor></CustomEditor>
    </div>
</template>

<script>
    import CustomEditor from './components/CustomEditor.vue'

    export default {
        name: 'app',
        components: {
            CustomEditor
        }
    }
</script>

<style>
    #app {
        font-family: 'Avenir', Helvetica, Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-align: center;
        color: #2c3e50;
        margin-top: 60px;
    }

    .vue-logo {
        width: 60px;
        max-width: 100%;
    }
</style>
