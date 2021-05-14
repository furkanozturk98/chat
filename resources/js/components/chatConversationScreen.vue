<template>
  <div v-if="conversation" class="col-md-9">
    <div class="settings-tray" :class="{'settings-tray-dark': nightMode}">
      <div
        class="friend-drawer no-gutters"
        :class="{ 'friend-drawer--grey' : !nightMode, 'friend-drawer--dark' : nightMode }"
        style="margin-bottom:-13px"
      >
        <img
          class="profile-image"
          :src="'images/'+conversation.friend.image"
          alt=""
        >
        <div class="text" :class=" {'text-white' : nightMode}">
          <h6>{{ conversation.friend.name }}</h6>
          <p :class="{'text-muted' :!nightMode, 'text-light' :nightMode,}">
            {{ conversation.friend.about }}
          </p>
        </div>

        <span v-if="conversation" class="settings-tray--right" style="margin-left:220px">
          <a
            id="dropdownMenu3"
            role="button"
            data-toggle="dropdown"
            style=" cursor: pointer"
          >
            <i class="material-icons">menu</i>
            <div class="dropdown-menu" aria-labelledby="dropdownMenu3">
              <button
                class="dropdown-item edit-message"
                type="button"
                @click="block(conversation.friend.id)"
              >Block</button>
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
            <b-dropdown-item @click="block(conversation.friend.id)">
              <b-icon icon="person-lines-fill" aria-hidden="true" />
              Block
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

        <message-list
          :key="messageListKey"
          :items="items"
          :current-user="currentUser"
          :selected-friend="conversation.friend"
        />
      </div>
    </div>

    <div class="row">
      <div class="col-12">
        <div class="chat-box-tray" :class="{ 'chat-box-tray-dark': nightMode}">
          <i class="material-icons">sentiment_very_satisfied</i>
          <input v-model="form.message" type="text" placeholder="Type your message here..." @keyup.enter="sendMessage">
          <a @click="sendMessage"><i class="material-icons">send</i></a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import EditMessageModal from './modals/editMessageModal';
import MessageList from './messageList';
import Form from 'form-backend-validation';

export default {
    name: 'ChatConversationScreen',
    components: { MessageList, EditMessageModal },
    props: ['currentUser',],

    data() {
        return {
            items: [],
            conversation: null,
            groupConversation: null,
            nightMode: false,
            messageListKey: 0,
            form: new Form({
                message: null,
            })
        }
    },

    mounted() {
        this.$eventHub.$on('friendClick', this.friendClicked);
        this.$eventHub.$on('groupClick', this.groupClicked);
        this.$eventHub.$on('nightModeOn', this.nightModeOn);
        this.nightMode = (localStorage.getItem('nightMode') === 'true')

    },

    methods: {

        async sendMessage() {
            if(this.form.message === null){
                return;
            }

            const message = this.form.message;
            await this.form.post('/api/message/send/' + this.conversation.roomId + '/to/' + this.conversation.friend.id);

            const lastItem = this.items[this.items.length - 1];

            const data = {
                'id': lastItem ? lastItem.id + 1 : 1,
                'from':   this.currentUser.id,
                'to': this.conversation.friend.id,
                message,
                'room_id': this.conversation.roomId,
            };

            this.items.push(data);
        },

        async fetch() {
            const response = await this.$http.get('/api/message/' + this.conversation.roomId);
            this.items = response.data.data;
        },

        friendClicked(conversation) {
            this.conversation = conversation

            this.fetch();
        },

        groupClicked() {
            this.conversation = null;
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

        forceRerender() {
            this.messageListKey += 1;
        },

        block(id) {

        }
    }

}
</script>
