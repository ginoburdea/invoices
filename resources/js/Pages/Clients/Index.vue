<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useForm, Head } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { ref } from "vue";
import Modal from "@/Components/Modal.vue";
import { reactive } from "vue";

defineProps(["clients"]);

const showModal = ref(false);

const emptyValues = { name: "", tax_id: "", address: "" };
const initialValues = reactive(emptyValues);

const form = useForm(initialValues);

const editingClientId = ref(null);

const submitForm = () => {
    const formOptions = {
        onSuccess: () => {
            form.reset();
            showModal.value = false;
            editingClientId.value = null;
        },
    };

    return editingClientId.value
        ? form.put(route("clients.update", editingClientId.value), formOptions)
        : form.post(route("clients.store"), formOptions);
};

const handleModalClose = () => {
    editingClientId.value = null;

    let hasChanged = false;
    for (const key in initialValues) {
        if (form[key] != initialValues[key]) {
            hasChanged = true;
            break;
        }
    }

    if (!hasChanged) {
        showModal.value = false;
        return;
    }

    const confirmMsg = "Are you sure you want to close without saving?";
    if (confirm(confirmMsg)) {
        form.reset();
        form.clearErrors();
        showModal.value = false;
    }
};

const openCreateModal = () => {
    showModal.value = true;

    for (const key in emptyValues) {
        initialValues[key] = emptyValues[key];
    }
};

const openEditModal = (client) => {
    showModal.value = true;
    editingClientId.value = client.id;

    for (const key in client) {
        form[key] = client[key];
        initialValues[key] = client[key];
    }
};

const deleteClient = (client) => {
    const confirmMsg = "Are you sure you want to delete this client?";
    if (confirm(confirmMsg)) {
        return form.delete(route("clients.destroy", client.id));
    }
};
</script>

<template>
    <Head title="clients" />
    <AuthenticatedLayout>
        <div
            class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8 w-full flex flex-col justify-center items-center"
        >
            <div class="w-full flex justify-between align-center mb-10">
                <h1 class="text-2xl font-extrabold">Clients</h1>
                <PrimaryButton @click="openCreateModal">
                    Add client
                </PrimaryButton>
            </div>

            <Modal :show="showModal" @close="handleModalClose">
                <form
                    @submit.prevent="submitForm"
                    class="flex flex-col items-start gap-4 p-6"
                >
                    <h1 class="text-xl">Add a client</h1>
                    <div class="w-full">
                        <InputLabel value="Name" />
                        <TextInput class="w-full" v-model="form.name" />
                        <InputError :message="form.errors.name" class="mt-2" />
                    </div>
                    <div class="w-full">
                        <InputLabel value="Tax id" />
                        <TextInput class="w-full" v-model="form.tax_id" />
                        <InputError
                            :message="form.errors.tax_id"
                            class="mt-2"
                        />
                    </div>
                    <div class="w-full">
                        <InputLabel value="Address" />
                        <TextInput class="w-full" v-model="form.address" />
                        <InputError
                            :message="form.errors.address"
                            class="mt-2"
                        />
                    </div>
                    <PrimaryButton :disabled="form.processing">
                        {{ editingClientId ? "Update" : "Create" }}
                    </PrimaryButton>
                </form>
            </Modal>

            <table class="w-full border-collapse border-2 border-gray-500">
                <thead class="bg-gray-500 text-white text-left">
                    <th class="px-4 py-1 border-2 border-gray-500">Name</th>
                    <th class="px-4 py-1 border-2 border-gray-500">Tax id</th>
                    <th class="px-4 py-1 border-2 border-gray-500">Actions</th>
                </thead>
                <tbody>
                    <tr v-for="client in $props.clients" :key="client.id">
                        <td class="px-4 py-1 border-2 border-gray-500">
                            {{ client.name }}
                        </td>
                        <td class="px-4 py-1 border-2 border-gray-500">
                            {{ client.tax_id || "-" }}
                        </td>
                        <td class="px-4 py-1 border-2 border-gray-500">
                            <div class="flex gap-1">
                                <SecondaryButton @click="openEditModal(client)">
                                    Edit
                                </SecondaryButton>
                                <SecondaryButton @click="deleteClient(client)">
                                    Delete
                                </SecondaryButton>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>
</template>
