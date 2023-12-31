<template>
  <header>
    <PageHeader
      page-title="Product Add"
      blue-button-text="Save"
      red-button-text="Cancel"
      @blue-button-click="addProduct"
      @red-button-click="cancel"
    />
  </header>

  <main>
    <form
      id="product_form"
      method="POST"
      :action="formAction"
      class="col-12"
    >
      <FormInputStyle
        label-for-attr="sku"
        label-text="SKU"
      >
        <input
          id="sku"
          v-model="formData.sku"
          class="form-control"
          name="sku"
          required
        />
        <p
          v-for="error in v$.formData.sku.$errors"
          :key="error.uid"
          class="text-danger"
          role="alert"
        >
          {{ error.$message }}
        </p>
      </FormInputStyle>

      <FormInputStyle
        label-for-attr="name"
        label-text="Name"
      >
        <input
          id="name"
          v-model="formData.name"
          class="form-control"
          name="name"
          required
        />
        <p
          v-for="error in v$.formData.name.$errors"
          :key="error.uid"
          class="text-danger"
          role="alert"
        >
          {{ error.$message }}
        </p>
      </FormInputStyle>

      <FormInputStyle
        label-for-attr="price"
        label-text="Price"
      >
        <input
          id="price"
          v-model="formData.price"
          type="number"
          class="form-control"
          name="price"
          required
        />
        <p
          v-for="error in v$.formData.price.$errors"
          :key="error.uid"
          class="text-danger"
          role="alert"
        >
          {{ error.$message }}
        </p>
      </FormInputStyle>

      <FormInput
        name="typeSwitcher"
        label="TypeSwitcher"
        custom-input
      >
        <template #customInput>
          <select
            id="productType"
            ref="typeSwitcher"
            v-model="formData.productTypeName"
            class="form-control form-select"
            name="productTypeName"
            required
          >
            <option
              value="DVD"
              data-attribute-component="DvdAttribute"
            >
              DVD
            </option>
            <option
              value="Furniture"
              data-attribute-component="FurnitureAttribute"
            >
              Furniture
            </option>
            <option
              value="Book"
              data-attribute-component="BookAttribute"
            >
              Book
            </option>
          </select>
        </template>
      </FormInput>

      <KeepAlive>
        <component :is="activeProductType"></component>
      </KeepAlive>

      <input
        v-show="false"
        ref="submitInput"
        type="submit"
        value="Submit"
      />
    </form>
  </main>
</template>

<script>
import { useVuelidate } from '@vuelidate/core';
import { helpers, required, numeric } from '@vuelidate/validators';
import { FormInputStyle } from '../components/ProductAdd';
import { FormInput } from '../components/ProductAdd';
import { DvdAttribute } from '../components/ProductAdd';
import { FurnitureAttribute } from '../components/ProductAdd';
import { BookAttribute } from '../components/ProductAdd';
import { BASE_API_URL } from './../globalConstants';

const preventDefautValue = (selected) => selected != 'default';

export default {
  components: {
    FormInputStyle,
    FormInput,
    DvdAttribute,
    FurnitureAttribute,
    BookAttribute,
  },
  setup() {
    return {
      v$: useVuelidate(),
    };
  },
  data() {
    return {
      formData: {
        sku: '',
        name: '',
        price: null,
        test: null,
        productTypeName: 'DVD',
        size: null,
        height: null,
        width: null,
        length: null,
        weight: null,
      },
      invalidTypeMessage: 'Please provide the indicated data type.',
      formAction: BASE_API_URL + '/add-product',
    };
  },
  computed: {
    activeProductType() {
      switch (this.formData.productTypeName) {
        case 'DVD':
          return 'DvdAttribute';
        case 'Furniture':
          return 'FurnitureAttribute';
        case 'Book':
          return 'BookAttribute';

        default:
          break;
      }
      return null;
    },
  },
  methods: {
    addProduct() {
      const submitInput = this.$refs.submitInput;

      submitInput.click();
    },
    cancel() {
      this.$router.push('/');
    },
  },
  validationConfig() {
    return {
      $autoDirty: true,
      $lazy: true,
      $rewardEarly: true,
    };
  },
  validations() {
    return {
      formData: {
        sku: {
          $autoDirty: true,
          required,
        },
        name: {
          $autoDirty: true,
          required,
        },
        price: {
          $autoDirty: true,
          required,
          numeric: helpers.withMessage(this.invalidTypeMessage, numeric),
        },
        productTypeName: {
          $autoDirty: true,
          required,
          preventDefautValue: helpers.withMessage(
            'Please select a product.',
            preventDefautValue,
          ),
        },
      },
    };
  },
};
</script>
