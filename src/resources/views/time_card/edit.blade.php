@extends('time_card.extends.form', [
  'type' => '更新',
  'timeCard' => $timeCard,
  'openForm' => [
    'method' => 'put',
    'route' => ['time_card.update', $timeCard->id ?? null]
  ],
  'buttonLabel' => '更新する',
])