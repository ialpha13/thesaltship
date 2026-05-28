﻿<?php
require_once __DIR__ . '/../includes/functions.php';

$whatsappNumber = '+923335036125';
$whatsappMessage = 'Hello Pakwest International, I am interested in your salt products. Could I get more details?';
$whatsappDigits = preg_replace('/\D+/', '', $whatsappNumber);
$whatsappUrl = 'https://wa.me/' . $whatsappDigits . '?text=' . rawurlencode($whatsappMessage);
?>
<div class="whatsapp-chat has-new-message" data-whatsapp-url="<?= h($whatsappUrl) ?>">
  <div class="whatsapp-panel" id="whatsapp-panel" hidden>
    <span class="whatsapp-panel-header">
      <span class="whatsapp-panel-avatar-wrap" aria-hidden="true">
        <img src="<?= h(base_url('assets/images/logopakwest.webp')) ?>" alt="" class="whatsapp-panel-avatar" loading="lazy">
      </span>
      <span class="whatsapp-panel-meta">
        <span class="whatsapp-panel-brand">Pakwest International</span>
        <span class="whatsapp-panel-status">online</span>
      </span>
    </span>

    <span class="whatsapp-panel-chat-area">
      <span class="whatsapp-panel-bubble">
        <strong class="whatsapp-panel-title">Welcome to Pakwest International</strong>
        <span class="whatsapp-panel-text">We are here to help with pricing, samples, and bulk orders.</span>
        <span class="whatsapp-panel-time">12:00</span>
      </span>
    </span>

    <span class="whatsapp-panel-input-row">
      <a class="whatsapp-start-chat" href="<?= h($whatsappUrl) ?>" target="_blank" rel="noopener" aria-label="Start chat on WhatsApp">
        Start Chat
      </a>
    </span>
  </div>
  <button class="whatsapp-button" aria-label="Open WhatsApp chat" aria-expanded="false" aria-controls="whatsapp-panel" type="button">
    <span class="whatsapp-icon-wrap" aria-hidden="true">
      <svg class="whatsapp-icon" viewBox="0 0 24 24" aria-hidden="true">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.41 0 .01 5.399 0 12.039c0 2.123.554 4.197 1.608 6.06L0 24l6.117-1.604a11.845 11.845 0 005.926 1.587h.005c6.638 0 12.038-5.4 12.04-12.04.002-3.218-1.248-6.242-3.517-8.511" />
      </svg>
    </span>
    <span class="whatsapp-message-badge" aria-hidden="true">1</span>
    <span class="whatsapp-message-hint" aria-hidden="true">New Message</span>
  </button>
</div>
