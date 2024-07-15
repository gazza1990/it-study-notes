-- Per buttare giù una sessione utilizzare il comando "kill <ID Session>"
SELECT  *
FROM    sys.dm_exec_connections AS blocking
    INNER JOIN sys.dm_exec_requests blocked
        ON blocking.session_id = blocked.blocking_session_id
    INNER JOIN sys.dm_os_waiting_tasks waitstats
        ON waitstats.session_id = blocked.session_id