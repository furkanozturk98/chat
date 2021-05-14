<template>
  <div>
    <b-modal
      ref="group-member-list"
      title="Group Members"
      size="lg"
      hide-footer
    >
      <div v-for="member in members">
        <div class="friend-drawer ">
          <img
            class="profile-image"
            :src="'images/'+member.member.image"
            alt=""
          >
          <div class="text" style="width:60%">
            <h6>
              {{ member.member.name }}
            </h6>

            <p class="text-muted">
              {{ member.member.about }}
            </p>
          </div>
          <span class="ml-3">
            <button
              v-if="member.type === 0 && currentMember.type === 2 && (member.member.id !== currentMember.member.id)"
              class="btn btn-outline-success"
              @click="makeAdmin(member.id)"
            >Make Admin</button>
            <button v-if="!isFriend(member.member.id)" class="btn btn-outline-success" @click="addFriend(member.id)"><b-icon
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
                        this.$http.put('/api/group-member/make-admin/' + memberId);

                        this.$refs['group-member-list'].hide();

                        Vue.$toast.open({
                            message: 'This user is now an admin',
                            type: 'success',
                            position: 'top-right',
                            duration: 1000
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

                        this.$http.post('/api/group-member/add-friend/' + memberId);

                        this.$refs['group-member-list'].hide();

                        Vue.$toast.open({
                            message: 'Friend Request is sent!',
                            type: 'success',
                            position: 'top-right',
                            duration: 1000
                        });

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
                        this.$http.delete('/api/group-member/remove/' + memberId);

                        this.$refs['group-member-list'].hide();

                        Vue.$toast.open({
                            message: 'User is removed from the group',
                            type: 'success',
                            position: 'top-right',
                            duration: 1000
                        });

                    }
                });

        },

        isFriend(memberId) {
            for (let i = 0; i < this.friends.length; i++) {
                if (this.friends[i].id === memberId) {
                    return true;
                }
            }

            return false;
        },

        async fetchFriends() {
            const response = await this.$http.get('/api/friend-list');
            this.friends = response.data.data;
            console.log(this.friends)
        }
    }
}
</script>

<style scoped>

</style>
