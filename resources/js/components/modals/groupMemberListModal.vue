<template>
  <div>
    <b-modal
      ref="group-member-list"
      title="Group Members"
      size="lg"
      hide-footer
    >
      <div v-for="member in members" :key="member.id">
        <div class="friend-drawer ">
          <img
            class="profile-image"
            :src="'images/'+member.member.image"
            alt=""
          >
          <div class="text" style="width:50%">
            <h6>
              {{ member.member.name }}
            </h6>

            <p class="text-muted">
              {{ member.member.about }}
            </p>
          </div>
          <span class="ml-5">
            <button
              v-if="member.type === 0 && currentMember.type === 2 && (member.member.id !== currentMember.member.id)"
              class="btn btn-outline-success"
              @click="makeAdmin(member.id)"
            >Make Admin</button>
            <button
              v-if="member.type === 1 && currentMember.type === 2 && (member.member.id !== currentMember.member.id)"
              class="btn btn-outline-danger"
              @click="dismissAsAdmin(member.id)"
            >Dismiss as admin</button>
            <button
              v-if="!isFriend(member.member.id) && member.member.id !== currentMember.member.id"
              class="btn btn-outline-success"
              @click="addFriend(member.id)"
            ><b-icon
              icon="person-plus-fill"
            /></button>
            <button
              v-if="(currentMember.type === 2 && member.type === 0) || (currentMember.type === 1 && member.type === 0) || (currentMember.type === 2 && member.type === 1) || (member.member.id === currentMember.member.id)"
              class="btn btn-outline-danger"
              @click="remove(member.id, member.group.id)"
            ><b-icon
              icon="x-circle"
            /></button>
          </span>
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
export default {
    name: 'GroupMemberListModal',
    props: ['currentMember', 'members', 'selectedGroup'],

    data() {
        return {
            friends: null
        }
    },

    mounted() {
        this.$eventHub.$on('showGroupMemberListModal', this.showGroupInvitesModal);
    },

    methods: {
        async showGroupInvitesModal() {
            await this.fetchFriends();
            this.$refs['group-member-list'].show();
        },

        makeAdmin(memberId) {
            this.$bvModal.msgBoxConfirm('Please confirm that you want to make this user an admin.', {
                title: 'Please Confirm',
                size: 'sm',
                buttonSize: 'sm',
                okVariant: 'danger',
                okTitle: 'YES',
                cancelTitle: 'NO',
                footerClass: 'p-2',
                hideHeaderClose: false,
                centered: true
            })
                .then(value => {
                    if (value) {
                        this.$http.put('/api/group-member/make-admin/' + memberId)
                            .then(() => {
                                this.$refs['group-member-list'].hide();

                                Vue.$toast.open({
                                    message: 'This user is now an admin',
                                    type: 'success',
                                    position: 'top-right',
                                    duration: 1000
                                });
console.log('memberid' + memberId)
                                this.members.forEach(item => {
                                    console.log(item.id)
                                    if (item.id === memberId) {
                                        item.type = 1;
                                    }
                                })

                            })
                            .catch(() => {
                                Vue.$toast.open({
                                    message: 'An error occurred!',
                                    type: 'success',
                                    position: 'top-right',
                                    duration: 1000
                                });
                            });
                    }
                });
        },

        addFriend(memberId) {
            this.$bvModal.msgBoxConfirm('Please confirm that you want to send friend request to this user.', {
                title: 'Please Confirm',
                size: 'sm',
                buttonSize: 'sm',
                okVariant: 'danger',
                okTitle: 'YES',
                cancelTitle: 'NO',
                footerClass: 'p-2',
                hideHeaderClose: false,
                centered: true
            })
                .then(value => {
                    if (value) {

                        this.$http.post('/api/group-member/add-friend/' + memberId)
                            .then((response) => {

                                this.$refs['group-member-list'].hide();

                                Vue.$toast.open({
                                    message: 'Friend Request is sent!',
                                    type: 'success',
                                    position: 'top-right',
                                    duration: 1000
                                });

                            })
                            .catch((error) => {
                                if (error.response.status === 422) {
                                    this.$bvModal.msgBoxOk('You already sent a friendship request to this user', {
                                        title: 'Error',
                                        size: 'sm',
                                        buttonSize: 'sm',
                                        okVariant: 'danger',
                                        headerClass: 'p-2 border-bottom-0',
                                        footerClass: 'p-2 border-top-0',
                                        centered: true
                                    })
                                }
                            })

                    }
                });
        },

        remove(memberId) {
            this.$bvModal.msgBoxConfirm('Please confirm that you want to remove this user from the group', {
                title: 'Please Confirm',
                size: 'sm',
                buttonSize: 'sm',
                okVariant: 'danger',
                okTitle: 'YES',
                cancelTitle: 'NO',
                footerClass: 'p-2',
                hideHeaderClose: false,
                centered: true
            })
                .then(value => {
                    if (value) {

                        this.$http.delete('/api/group-member/remove/' + memberId)
                            .then((response) => {

                                console.log(response.data.data);

                                this.$refs['group-member-list'].hide();

                                this.$eventHub.$emit('groupMemberRemove', response.data.data);

                                Vue.$toast.open({
                                    message: 'User is removed from the group',
                                    type: 'success',
                                    position: 'top-right',
                                    duration: 1000
                                });
                            })
                            .catch((error) => {
                                Vue.$toast.open({
                                    message: 'An error occurred!',
                                    type: 'error',
                                    position: 'top-right',
                                    duration: 600
                                });
                            })

                    }
                });

        },

        dismissAsAdmin(memberId) {
            this.$bvModal.msgBoxConfirm('Please confirm that you want to dismiss as admin this user.', {
                title: 'Please Confirm',
                size: 'sm',
                buttonSize: 'sm',
                okVariant: 'danger',
                okTitle: 'YES',
                cancelTitle: 'NO',
                footerClass: 'p-2',
                hideHeaderClose: false,
                centered: true
            })
                .then(value => {
                        if (value) {

                            this.$http.put('/api/group-member/dismiss-as-admin/' + memberId)

                                .then(() => {
                                    this.$refs['group-member-list'].hide();

                                    Vue.$toast.open({
                                        message: 'This user is now a normal user',
                                        type: 'success',
                                        position: 'top-right',
                                        duration: 1000
                                    });

                                    this.members.forEach(item => {
                                        if (item.id === memberId) {
                                            item.type = 0;
                                        }
                                    })
                                })
                                .catch(() => {
                                    Vue.$toast.open({
                                        message: 'An error occurred!',
                                        type: 'error',
                                        position: 'top-right',
                                    })
                                });
                        }
                    }
                );
        },

        isFriend(memberId) {
            for (let i = 0; i < this.friends.length; i++) {
                if (this.friends[i].friend.id === memberId) {
                    return true;
                }
            }

            return false;
        },

        async fetchFriends() {
            const response = await this.$http.get('/api/friend-list');
            this.friends = response.data.data;
        }
    }
}
</script>

<style scoped>

</style>
