<template>
  <div>
    <b-modal
      ref="group"
      title="Create Group"
      @show="resetModal"
      @hidden="resetModal"
      @ok="handleOk"
    >
      <form ref="form" @submit.prevent="submit">
        <b-form-group
          label="Name"
          label-for="name-input"
          :invalid-feedback="form.errors.first('name')"
        >
          <b-form-input
            id="name-input"
            v-model="form.name"
            :state="form.errors.has('name') ? false : null"
            required
          />
        </b-form-group>
      </form>
    </b-modal>
  </div>
</template>

<script>
import Form from 'form-backend-validation';

export default {
    name: 'CreateGroupModal',

    data() {
        return {
            form: new Form({
                    name: null
                },
            ),
        }
    },

    mounted() {
        this.$eventHub.$on('showCreateGroupModal', this.showCreateGroupModal);
    },

    methods: {

        showCreateGroupModal() {
            this.$refs['group'].show();
        },

        async submit() {
            await this.form.post('/api/group');

            this.$refs['group'].hide();

            Vue.$toast.open({
                message: 'Group is created!',
                type: 'success',
                position: 'top-right',
                duration: 600
            });
        },

        resetModal() {
            this.form.name = ''
        },

        handleOk(bvModalEvt) {
            bvModalEvt.preventDefault();

            this.submit();
        },
    }
}
</script>
