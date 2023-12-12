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

defineProps(["products"]);

const showModal = ref(false);

const emptyValues = { name: "", sku: "", price: 0, tax_percentage: 0 };
const initialValues = reactive(emptyValues);

const form = useForm(initialValues);

const editingProductId = ref(null);

const submitForm = () => {
    const formOptions = {
        onSuccess: () => {
            form.reset();
            showModal.value = false;
            editingProductId.value = null;
        },
    };

    return editingProductId.value
        ? form.put(
              route("products.update", editingProductId.value),
              formOptions
          )
        : form.post(route("products.store"), formOptions);
};

const handleModalClose = () => {
    editingProductId.value = null;

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

const openEditModal = (product) => {
    showModal.value = true;
    editingProductId.value = product.id;

    for (const key in product) {
        form[key] = product[key];
        initialValues[key] = product[key];
    }
};

const deleteProduct = (product) => {
    const confirmMsg = "Are you sure you want to delete this product?";
    if (confirm(confirmMsg)) {
        return form.delete(route("products.destroy", product.id));
    }
};
</script>

<template>
    <Head title="Products" />
    <AuthenticatedLayout>
        <div
            class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8 w-full flex flex-col justify-center items-center"
        >
            <div class="w-full flex justify-between align-center mb-10">
                <h1 class="text-2xl font-extrabold">Products</h1>
                <PrimaryButton @click="openCreateModal">
                    Add product
                </PrimaryButton>
            </div>

            <Modal :show="showModal" @close="handleModalClose">
                <form
                    @submit.prevent="submitForm"
                    class="flex flex-col items-start gap-4 p-6"
                >
                    <h1 class="text-xl">Add a product</h1>
                    <div class="w-full">
                        <InputLabel value="Name" />
                        <TextInput class="w-full" v-model="form.name" />
                        <InputError :message="form.errors.name" class="mt-2" />
                    </div>
                    <div class="w-full">
                        <InputLabel value="SKU" />
                        <TextInput class="w-full" v-model="form.sku" />
                        <InputError :message="form.errors.sku" class="mt-2" />
                    </div>
                    <div class="w-full">
                        <InputLabel value="Price (tax excl.)" />
                        <TextInput
                            class="w-full"
                            v-model="form.price"
                            type="number"
                            step=".01"
                        />
                        <InputError :message="form.errors.price" class="mt-2" />
                    </div>
                    <div class="w-full">
                        <InputLabel value="Tax percentage" />
                        <TextInput
                            class="w-full"
                            v-model="form.tax_percentage"
                            type="number"
                            step="1"
                        />
                        <InputError
                            :message="form.errors.tax_percentage"
                            class="mt-2"
                        />
                    </div>
                    <PrimaryButton :disabled="form.processing">
                        {{ editingProductId ? "Update" : "Create" }}
                    </PrimaryButton>
                </form>
            </Modal>

            <table class="w-full border-collapse border-2 border-gray-500">
                <thead class="bg-gray-500 text-white text-left">
                    <th class="px-4 py-1 border-2 border-gray-500">Name</th>
                    <th class="px-4 py-1 border-2 border-gray-500">SKU</th>
                    <th class="px-4 py-1 border-2 border-gray-500">
                        Price (without tax)
                    </th>
                    <th class="px-4 py-1 border-2 border-gray-500">Tax</th>
                    <th class="px-4 py-1 border-2 border-gray-500">Actions</th>
                </thead>
                <tbody>
                    <tr v-for="product in $props.products" :key="product.id">
                        <td class="px-4 py-1 border-2 border-gray-500">
                            {{ product.name }}
                        </td>
                        <td class="px-4 py-1 border-2 border-gray-500">
                            {{ product.sku || "-" }}
                        </td>
                        <td class="px-4 py-1 border-2 border-gray-500">
                            {{ product.price }}
                        </td>
                        <td class="px-4 py-1 border-2 border-gray-500">
                            {{ product.tax_percentage }}%
                        </td>
                        <td class="px-4 py-1 border-2 border-gray-500">
                            <div class="flex gap-1">
                                <SecondaryButton
                                    @click="openEditModal(product)"
                                >
                                    Edit
                                </SecondaryButton>
                                <SecondaryButton
                                    @click="deleteProduct(product)"
                                >
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
