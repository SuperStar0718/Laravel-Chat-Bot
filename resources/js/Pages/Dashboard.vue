<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { useForm } from "@inertiajs/vue3";
import { ref } from 'vue'
import Message from "@/Pages/Messages/Message.vue";
import TextInput from "@/Components/TextInput.vue";
import { onMounted } from 'vue';

const messages = ref([]);
const loading = ref(false);
const page = usePage();
const form = useForm({
    userMessage: '',
})
const count = ref(1);

onMounted(async () => {
    console.log('Component is mounted!');
    const response = await axios.post('/get_history');
    console.log(response.data);
    response.data.messages.forEach((message) => {
        messages.value.push({ content: message.content, sender: message.sender });
    })
    // Your mounted logic here
});

const store = async () => {
    loading.value = true;
    messages.value.push({ content: form.userMessage, sender: page.props.auth.user.name });
    try {
        const response = await axios.post('/chat', {
            'userMessage': form.userMessage
        })
        messages.value.push({ content: response.data.message, sender: 'ChatGPT' });
        form.userMessage = '';
    } catch (error) {
        console.log(error)
    }
    loading.value = false;
}



const deleteMessage = async (index) => {
    //get message from index
    const message = messages.value[index];
    console.log('message', message);
    //remove message from array
    messages.value.splice(index, 1);
    const response = await axios.post('/delete_message', {
        'message': message.content
    })
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Dashboard</h2>
        </template>

        <div class="h-full py-12">
            <div class="h-full mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="flex flex-col h-full bg-white rounded-md dark:bg-gray-800">
                    <div class="flex items-center justify-between px-4 py-3 lg:px-6">
                        <div class="relative flex items-center space-x-2">
                            <span class="relative bottom-0 right-0 w-2 h-2 bg-green-500 rounded-full">&nbsp;</span>
                            <h2 class="text-base font-semibold text-gray-800 dark:text-white">ChatGPT is online</h2>
                        </div>
                    </div>
                    <div
                        class="flex flex-col px-4 pb-8 space-y-4 border-t border-gray-200 grow lg:px-6 dark:border-gray-700">
                        <div class="flex flex-col min-h-0 space-y-4 overflow-y-auto grow basis-0">
                            <Message v-for="(message, index) in messages" :key="message.id" :message="message"
                                @deleteMessage="deleteMessage(index)" />
                        </div>

                        <div class="flex items-center pt-3">
                            <img src="images/user.jpg" class="w-12 h-12 mr-3 rounded-full"
                                :alt="page.props.auth.user.name" />
                            <div class="flex-1">
                                <form @submit.prevent="store">
                                    <TextInput v-model="form.userMessage" type="text"
                                        class="w-full px-4 py-3 text-sm placeholder-gray-400 border border-gray-200 rounded-md"
                                        placeholder="Send a message" :disabled="loading === true" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
