<b-form inline action="" method="GET">
  <b-container>
    <b-row>
      <b-col cols="3">
        <b-form-input placeholder="Cerca" name="q" v-model="q" type="search"></b-form-input>
      </b-col>
      <b-col cols="3">
        <v-select placeholder="Tags" :options="tagList" name="tags" v-model="tagArray" multiple label="name" @search="fetchTags" style="width:100%">
        </v-select>
      </b-col>
      <b-col cols="2">
        <b-form-input name="provincia" v-model="provincia" placeholder="Provincia" size="2" type="text" style="width: 5em;" title="Puoi separare più sigle di Province con la virgola" v-b-tooltip.hover />
      </b-col>
      <b-col cols="3">
        <b-form-input name="nazione" v-model="nazione" placeholder="Nazione" title="Puoi separare più sigle di Nazione con la virgola" v-b-tooltip.hover />
      </b-col>
      <b-col cols="1">
        <b-button class="float-right" type="submit" @click.prevent="search()">Filtra</b-button>
      </b-col>
    </b-row>

    <b-row cols="12" class="float-right mt-2">
      <b-col>
        <b-link class="btn btn-primary btn-sm" :href="`/campaigns/edit${qString}`" title="Invia mail alla lista" v-b-tooltip.hover size="sm">
          <b-icon-envelope-fill></b-icon-envelope-fill>
          Invia Mail
        </b-link>
        <b-link class="btn btn-primary btn-sm" @click="addTag()" title="Aggiungi tag ai selezionati" v-b-tooltip.hover size="sm">
          <b-icon-tags>
          </b-icon-tags> Aggiungi Tag
        </b-link>
        <b-link class="btn btn-primary btn-sm" href="/persone/add" title="Aggiungi una persona" v-b-tooltip.hover size="sm">
          <b-icon-plus-circle>
          </b-icon-plus-circle> Aggiungi Contatto
        </b-link>
        <b-link class="btn btn-primary btn-sm" @click="deleteMulti()" title="Elimina righe selezionate" v-b-tooltip.hover size="sm">
          <b-icon-trash>
          </b-icon-trash> Elimina
        </b-link>
      </b-col>
    </b-row>
  </b-container>
  <b-table :items="fetchRows" :fields="colonne" class="mt-2" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" :busy.sync="isBusy" id="contacts" striped ref="tab" selectable select-mode="multi" @row-selected="onRowSelected">
    <!-- A custom formatted header cell for field 'id' -->
    <template #head(id)="data">
      <b-form-checkbox id="checkbox-1" v-model="selectAllStatus" name="checkbox-1" :value="true" :unchecked-value="false" @change="selectAllRows()">
      </b-form-checkbox>
    </template>

    <!-- Example scoped slot for select state illustrative purposes -->
    <template #cell(id)="{ rowSelected }">
      <template v-if="rowSelected">
        <span aria-hidden="true">
          <b-icon icon="check-square"></b-icon>
        </span>
        <span class="sr-only">Selected</span>
      </template>
      <template v-else>
        <span aria-hidden="true">
          <b-icon icon="square"></b-icon>
        </span>
        <span class="sr-only">Not selected</span>
      </template>
    </template>


    <template v-slot:cell(modified)="row">
      {{niceDate(row.item.modified)}}
    </template>

    <template v-slot:cell(DisplayName)="row">
      <span v-if="row.item.DisplayName">{{row.item.DisplayName}}</span>
      <span v-else>{{row.item.Nome}} {{row.item.Cognome}} {{row.item.Societa}}</span>
      <b-badge variant="primary" v-for="b in row.item.tag_list.split(',')" class="mr-1">{{b}}</b-badge><br>
      <small class="text-muted" v-if="row.item.DisplayName != `${row.item.Nome} ${row.item.Cognome}`">{{row.item.Nome}} {{row.item.Cognome}} {{row.item.Societa}}</small>
    </template>

    <template v-slot:cell(azioni)="row">
      <a class="action" :href="`/persone/edit/${row.item.id}`">
        <b-icon-pencil></b-icon-pencil>
      </a>
      <b-link class="action" @click.prevent="delPersone(row.item.id)">
        <b-icon-trash></b-icon-trash>
      </b-link>
      <a class="action" :href="`/attivita/add/${row.item.id}`">
        <b-icon-bullseye></b-icon-bullseye>
      </a>
    </template>
  </b-table>
</b-form>


<ul class="pagination justify-content-center pagination-md">
  <li class="page-item">
    <a class="page-link" @click.prevent="changePage(pagination.page - 1)" :disabled="pagination.page <= 1">«</a>
  </li>
  <li v-for="page in pages" class="page-item" :class="isCurrentPage(page) ? 'active' : ''">
    <a class="page-link" :class="isCurrentPage(page) ? 'active' : ''" @click.prevent="changePage(page)">{{ page }}</a>
  </li>
  <li class="page-item">
    <a class="page-link" @click.prevent="changePage(pagination.page + 1)" :disabled="pagination.page >= pagination.pageCount">»</a>
  </li>
</ul>

<?= $this->Html->script('node_modules/axios/dist/axios.min.js', ['block' => 'pre-vue']) ?>
<?= $this->Html->script('node_modules/luxon/build/global/luxon.min', ['block' => 'pre-vue']) ?>
<?= $this->Html->script('node_modules/vue-router/dist/vue-router.min.js', ['block' => 'pre-vue']) ?>
<?= $this->Html->script('/js/node_modules/vue-select/dist/vue-select', ['block' => 'pre-vue']) ?>
<?= $this->Html->css('/js/node_modules/vue-select/dist/vue-select', ['block' => true])  ?>