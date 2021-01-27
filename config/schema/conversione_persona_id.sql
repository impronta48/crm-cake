IGAS -> colonne riferimento persona.id (max 334)
attivita.cliente_id
faseattivita.persona_id
fatturericevute.fornitore_id
impiegati.persona_id
notaspese.eRisorsa
ore.eRisorsa
primanota.persona_id
users.persona_id
persona.id

update persone set id=id+10000;
update attivita set cliente_id=cliente_id+10000;
update faseattivita set persona_id=persona_id+10000;
update fatturericevute set fornitore_id=fornitore_id+10000;
update impiegati set persona_id=persona_id+10000;
update notaspese set eRisorsa = eRisorsa+10000;
update ore set eRisorsa = eRisorsa+10000;
update primanota set persona_id=persona_id+10000;
update users set  persona_id=persona_id+10000;


PRENOTA -> colonne riferimento persona.id (6962)
bici.noleggiatore_id
commerciali.id
contacts.persona_id
contacts.commerciale_id
contacts.gestore_id
contacts.user_id
contacts_partners.partner_id
contacts_percorsi.contact_id
faseattivita.persona_id
fatture.partner_id
gestori.id
messaging_conversations.author_id
messaging_conversations_users.user_id
messaging_messages.from_id
noleggiatori.id
noleggiatori_pickuppoints.noleggiatore_id
partners.id
partners.referral_id
partners_statuses.partner_id
service.cliente_id
tracks.user_id
users.id
users.noleggiatore_id

