<template>
  <div v-if="groupConversation" class="col-md-9">
    <add-group-member-modal :selected-group="groupConversation.id" />
    <group-member-list-modal :selected-group="groupConversation.id" :current-member="currentMember" :members="groupMembers" />
    <div class="settings-tray" :class="{'settings-tray-dark': nightMode}">
      <div
        class="friend-drawer no-gutters"
        :class="{ 'friend-drawer--grey' : !nightMode, 'friend-drawer--dark' : nightMode }"
        style="margin-bottom:-13px"
      >
        <img
          class="profile-image"
          :src="'images/'+groupConversation.image"
          alt=""
        >
        <div class="text" :class=" {'text-white' : nightMode}">
          <h6>{{ groupConversation.name }}</h6>
          <p :class="{'text-muted' :!nightMode, 'text-light' :nightMode,}">
            {{ groupConversation.about }}
          </p>
        </div>

        <span v-if="groupConversation" class="settings-tray--right" style="margin-left:220px">
          <a
            id="dropdownMenu2"
            role="button"
            data-toggle="dropdown"
            style=" cursor: pointer"
          >
            <i class="material-icons">menu</i>
            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
              <button
                v-if="currentMember.type !== 0"
                class="dropdown-item group-member-list"
                type="button"
                @click="showAddGroupMemberModal"
              >Add Group Member</button>
              <button
                class="dropdown-item group-member-list"
                type="button"
                @click="showGroupMemberListModal"
              >Group Member List</button>
            </div>
          </a>

          <b-dropdown
            ref="dropdown"
            right
            size="lg"
            variant="link"
            toggle-class="text-decoration-none"
            no-caret
          >
            <b-dropdown-item>
              <b-icon icon="person-lines-fill" aria-hidden="true" />
              Add Group Member
            </b-dropdown-item>
            <b-dropdown-item>
              <b-icon icon="person-lines-fill" aria-hidden="true" />
              Group Member List
            </b-dropdown-item>
          </b-dropdown>

        </span>
      </div>
    </div>

    <div class="chat-panel" :class="{ 'chat-panel-dark': nightMode } ">
      <div class="overflow-auto" style="height:630px;">
        <!--
                    <div class="row no-gutters">
                      <div class="col-md-3">
                        <div class="chat-bubble chat-bubble&#45;&#45;left">
                          <div class="chat-buble-name">
                            Robo Cop
                          </div> &lt;!&ndash; show only in groups &ndash;&gt;
                          Hello dude!
                          <span style="float:right">
                            <a
                              id="dropdownMenu2"
                              role="button"
                              data-toggle="dropdown"
                              style=" cursor: pointer"
                            >
                              <i class="bi bi-chevron-down" />
                              <div class="dropdown-menu" aria-labelledby="dropdownMenu2">

                                <button
                                  class="dropdown-item edit-message"
                                  type="button"
                                  data-toggle="modal"
                                  data-target="#editMessage"
                                >Edit Message</button>

                                <button
                                  class="dropdown-item delete-message"
                                  type="button"
                                  data-toggle="modal"
                                  data-target="#deleteMessage"
                                >Delete
                                  Message</button>
                              </div>
                            </a>
                          </span>
                          <div class="chat-buble-time">
                            15:52
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row no-gutters">
                      <div class="col-md-3 offset-md-9">
                        <div class="chat-bubble chat-bubble&#45;&#45;right">
                          <div class="chat-buble-name">
                            Robo Cop
                          </div> &lt;!&ndash; show only in groups &ndash;&gt;
                          Hello dude!
                          <span style="float:right">
                            <a
                              id="dropdownMenu3"
                              role="button"
                              data-toggle="dropdown"
                              style=" cursor: pointer"
                            >
                              <i class="bi bi-chevron-down" />
                              <div class="dropdown-menu" aria-labelledby="dropdownMenu2">

                                <button
                                  class="dropdown-item edit-message"
                                  type="button"
                                  data-toggle="modal"
                                  data-target="#editMessage"
                                >Edit Message</button>

                                <button
                                  class="dropdown-item delete-message"
                                  type="button"
                                  data-toggle="modal"
                                  data-target="#deleteMessage"
                                >Delete
                                  Message</button>
                              </div>
                            </a>
                          </span>
                          <div class="chat-buble-time">
                            15:52
                          </div>
                        </div>
                      </div>
                    </div>-->

        <edit-message-modal />

        <group-message-list
          :key="messageListKey"
          :items="items"
          :current-user="currentUser"
          :selected-group="groupConversation.id"
        />
      </div>

        <Picker set="twitter" />
    </div>


    <div class="row">
      <div class="col-12">
        <div class="">
          <div class="chat-box-tray" :class="{ 'chat-box-tray-dark': nightMode}">
            <i class="material-icons">sentiment_very_satisfied</i>
              <input v-model="form.message" type="text" placeholder="Type your message here..." @keyup.enter="sendMessage">
            <a @click="sendMessage"><i class="material-icons">send</i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import GroupMessageList from './groupMessageList';
import EditMessageModal from './modals/editMessageModal';
import Form from 'form-backend-validation';
import AddGroupMemberModal from './modals/addGroupMemberModal';
import GroupMemberListModal from './modals/groupMemberListModal';
import { Picker } from 'emoji-mart-vue'

export default {
    name: 'GroupChatConversationScreen',
    components: {
        AddGroupMemberModal,
        GroupMessageList,
        EditMessageModal,
        GroupMemberListModal,
        Picker
    },
    props: ['currentUser',],

    data() {
        return {
            items: [],
            groupConversation: null,
            groupMembers: null,
            nightMode: false,
            messageListKey: 0,
            currentMember: null,
            form: new Form({
                message: null,
                group_id: null,
                member_id: null
            }),
        }
    },

    mounted() {
        this.$eventHub.$on('groupClick', this.groupClicked);
        this.$eventHub.$on('friendClick', this.friendClicked);
        this.$eventHub.$on('nightModeOn', this.nightModeOn);
        this.nightMode = (localStorage.getItem('nightMode') === 'true')
    },
    methods: {
        async fetch() {
            const response = await this.$http.get('/api/group-messages/' + this.groupConversation.id);
            this.items = response.data.data;
        },

        async getGroupMembers(){
            const response = await this.$http.get('/api/group-member/' + this.groupConversation.id);
            this.groupMembers = response.data.data;

            for(let i=0;i< this.groupMembers.length; i++)
            {
                if(this.groupMembers[i].member.id === this.currentUser.id){
                    console.log(this.currentUser);
                    this.currentMember = this.groupMembers[i];
                    console.log(this.currentMember);
                }
            }

        },

        async sendMessage() {
            if(this.form.message === null){
                return;
            }
            this.form.group_id = this.groupConversation.id;
            this.form.member_id = this.currentMember.id;

            const message = this.form.message;

            const response = await this.form.post('/api/group-message');

            console.log(response.data.data);

            const lastItem = this.items[this.items.length - 1]
            const data = {
                'id': lastItem.id + 1,
                'sender':   this.currentMember.id,
                message,
            };

            this.items.push(data);
        },

        groupClicked(groupConversation) {
            this.groupConversation = groupConversation

            this.fetch();

            this.getGroupMembers();
        },

        friendClicked() {
            this.groupConversation = null;
        },

        nightModeOn() {
            if (this.nightMode === true) {
                this.nightMode = false;
                localStorage.setItem('nightMode', false);
            } else {
                this.nightMode = true;
                localStorage.setItem('nightMode', true);
            }
        },

        showAddGroupMemberModal(){
            this.$eventHub.$emit('showAddGroupMemberModal');
        },

        showGroupMemberListModal(){
            this.$eventHub.$emit('showGroupMemberListModal',this.groupConversation);
        }
    }
}
</script>
