<template>
  <div>
    <b-modal
      ref="add-group-member"
      title="Add Group Member"
      @show="resetModal"
      @hidden="resetModal"
      @ok="handleOk"
    >
      <form ref="form" @submit.prevent="submit">
        <b-form-group
          label="Email"
          label-for="email-input"
          :invalid-feedback="form.errors.first('email')"
        >
          <b-form-input
            id="email-input"
            v-model="form.email"
            :state="form.errors.has('email') ? false : null"
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
name: 'AddGroupMemberModal',

    props: ['selectedGroup'],

    data(){
        return {
            form: new Form({
                    email: null
                },
            ),
            groupId: null,
        }
    },

    mounted(){
        this.$eventHub.$on('showAddGroupMemberModal',this.showAddGroupMemberModal);
    },

    methods : {
        showAddGroupMemberModal(){
            this.$refs['add-group-member'].show();
        },

        async submit() {
            await this.form.post('/api/group-invite/' + this.selectedGroup);

            this.$refs['add-group-member'].hide();

            Vue.$toast.open({
                message: 'Group Invite is sent!',
                type: 'success',
                position: 'top-right',
                duration: 600
            });
        },

        resetModal() {
            this.form.email = ''
        },

        handleOk(bvModalEvt) {
            bvModalEvt.preventDefault();

            this.submit();
        },
    }
}
</script>

<style scoped>

</style>
